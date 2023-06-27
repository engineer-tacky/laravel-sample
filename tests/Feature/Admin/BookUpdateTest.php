<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Book;
use App\Models\Author;

class BookUpdateTest extends TestCase
{
  use RefreshDatabase;

  private $admin;
  private $categories;
  private $book;
  private $authors;

  public function setUp(): void
  {
    parent::setUp();

    $this->admin = Admin::factory()->create([
      'login_id' => 'hoge',
      'password' => \Hash::make('hogehoge'),
    ]);

    $this->categories = Category::factory(3)->create();

    $this->book = Book::factory()->create([
      'title' => 'Laravel Book',
      'admin_id' => $this->admin->id,
      'category_id' => $this->categories[1]->id,
    ]);

    $this->authors = Author::factory(4)->create();

    $this->book->authors()->attach([
      $this->authors[0]->id,
      $this->authors[2]->id,
    ]);
  }

  /** @test */
  public function 画面のアクセス制御(): void
  {
    $url = route('book.edit', $this->book);

    // 未認証の場合、更新画面にアクセス不可
    $this->get($url)
      ->assertRedirect(route('admin.create'));

    // 書籍の作成者とは異なるユーザーで認証
    $this->actingAs(Admin::factory()->create(), 'admin');

    // 書籍の作成者ではない場合、更新画面にアクセス不可
    $this->get($url)
      ->assertForbidden();

    // 書籍の作成者で認証
    $this->actingAs($this->admin, 'admin');

    // 書籍の作成者の場合、更新画面にアクセス可
    $this->get($url)
      ->assertOk();
  }

  /** @test */
  public function 更新のアクセス制御(): void
  {
    $url = route('book.update', $this->book);

    // 入力データ
    $param = [
      'category_id' => $this->categories[0]->id,
      'title' => 'New Laravel Book',
      'price' => 10000,
      'author_ids' => [
        $this->authors[1]->id,
        $this->authors[2]->id,
      ],
    ];

    // 未認証の場合、更新不可
    $this->put($url, $param)
      ->assertRedirect(route('admin.create'));

    // 書籍の作成者とは異なるユーザーで認証
    $this->actingAs(Admin::factory()->create(), 'admin');

    // 書籍の作成者ではない場合、更新不可
    $this->put($url, $param)
      ->assertForbidden();

    // 書籍が更新されていないこと
    $this->assertSame('Laravel Book', $this->book->fresh()->title);
  }

  /** @test */
  public function バリデーション(): void
  {
    // 作成者で認証
    $this->actingAs($this->admin, 'admin');

    $url = route('book.update', $this->book);

    // リダイレクト先の確認
    $this->from(route('book.edit', $this->book))
      ->put($url, ['category_id' => ''])
      ->assertRedirect(route('book.edit', $this->book));

    // 以降バリデーションとメッセージの確認
    // カテゴリIDが空
    $this->put($url, ['category_id' => ''])
      ->assertInvalid(['category_id' => 'カテゴリー は必須']);

    // カテゴリIDが0(存在しない)
    $this->put($url, ['category_id' => 0])
      ->assertInvalid(['category_id' => '正しい カテゴリ']);

    // カテゴリIDが3つ目のカテゴリID(存在する)
    $this->put($url, ['category_id' => $this->categories[2]->id])
      ->assertValid('category_id');

    // タイトルが空
    $this->put($url, ['title' => ''])
      ->assertInvalid(['title' => 'タイトル は必須']);

    // タイトルが1文字(正常)
    $this->put($url, ['title' => 'a'])
      ->assertValid('title');

    // タイトルが100文字(正常)
    $this->put($url, ['title' => str_repeat('a', 100)])
      ->assertValid('title');

    // タイトルが101文字(異常)
    $this->put($url, ['title' => str_repeat('a', 101)])
      ->assertInvalid(['title' => 'タイトル は 100 文字以下']);

    // 価格が数値ではない
    $this->put($url, ['price' => 'a'])
      ->assertInvalid(['price' => '価格 は数値']);

    // 価格が0(異常)
    $this->put($url, ['price' => 0])
      ->assertInvalid(['price' => '価格 は 1 以上']);

    // 価格が1(正常)
    $this->put($url, ['price' => 1])
      ->assertValid('price');

    // 価格が999999(正常)
    $this->put($url, ['price' => 999999])
      ->assertValid('price');

    // 価格が1000000(異常)
    $this->put($url, ['price' => 1000000])
      ->assertInvalid(['price' => '価格 は 999999 以下']);

    // 著者IDが空
    $this->put($url, ['author_ids' => []])
      ->assertInvalid(['author_ids' => '著者 は必須']);

    // 著者IDが0(存在しない)
    $this->put($url, ['author_ids' => [0]])
      ->assertInvalid(['author_ids.0' => '正しい 著者']);

    // 著者IDが3つ目の著者ID(存在する)
    $this->put($url, ['author_ids' => [$this->authors[2]->id]])
      ->assertValid('author_ids.0');
  }

  /** @test */
  public function 更新処理(): void
  {
    $url = route('book.update', $this->book);

    // 入力データ
    $param = [
      'category_id' => $this->categories[0]->id,
      'title' => 'New Laravel Book',
      'price' => 10000,
      'author_ids' => [
        $this->authors[1]->id,
        $this->authors[2]->id,
      ],
    ];

    // 作成者で認証
    $this->actingAs($this->admin, 'admin');

    // 更新後、書籍一覧画面にリダイレクト
    $this->put($url, $param)
      ->assertRedirect(route('book.index'));

    // 書籍が更新されていること
    $this->assertDatabaseHas('books', [
      'id' => $this->book->id,
      'category_id' => $param['category_id'],
      'title' => $param['title'],
      'price' => $param['price'],
    ]);

    // 書籍と著者の関連付けが削除され、新しい著者と関連付けが追加されていること
    foreach ($this->authors as $author) {
      $authorBook = [
        'book_id' => $this->book->id,
        'author_id' => $author->id,
      ];

      // パラメータで指定された著者IDか否かを判定
      if (in_array($author->id, $param['author_ids'])) {
        $this->assertDatabaseHas('author_book', $authorBook);
      } else {
        $this->assertDatabaseMissing('author_book', $authorBook);
      }
    }

    // フラッシュメッセージの確認
    $this->get(route('book.index'))
      ->assertSee($param['title'] . 'を更新しました');
  }
}
