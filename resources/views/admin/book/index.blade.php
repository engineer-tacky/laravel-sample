<x-layouts.book-manager title="書籍一覧">
  <h1>書籍一覧</h1>
  @if (session('message'))
    <x-alert class="info">
      {{ session('message') }}
    </x-alert>
  @endif
  <a href="{{route('book.create')}}">追加</a>
  <x-book-table :$books />
</x-layouts.book-manager>