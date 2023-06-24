<table border="1">
  <tr>
    <th>カテゴリ</th>
    <th>書籍名</th>
    <th>価格</th>
    <th>更新</th>
    <th>削除</th>
  </tr>
  @foreach ($books as $book)
    <tr @if ($loop->even) style="background:#E0E0E0" @endif>
      <td>{{ $book->category->title }}</td>
      <td>
        <a href="{{ route('book.show', $book) }}">
          {{ $book->title }}
        </a>
      </td>
      <td>{{ $book->price }}</td>
      <td>
        <a href="{{ route('book.edit', $book) }}">
          <button>更新</button>
        </a>
      </td>
      <td>
        <form action="{{ route('book.destroy', $book) }}" method="POST">
          @csrf
          @method('DELETE')
          <input type="submit" value="削除">
        </form>
      </td>
    </tr>
  @endforeach
</table>