<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Message;

class MessageTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function メッセージ一覧の表示(): void
  {
    Message::create(['body' => 'Hello World']);
    Message::create(['body' => 'Hello Laravel']);
    
    $this->get('messages')
      ->assertOk()
      ->assertSeeInOrder([
        'Hello World',
        'Hello Laravel'
      ]);
  }
}
