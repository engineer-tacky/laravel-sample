<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>書籍一覧</title>
</head>
<body>
  <main>
    <h1>書籍一覧</h1>
    @if (session('message'))
      <div style="color:blue;">
        {{ session('message') }}
      </div>
    @endif
    <a href="{{route('book.create')}}">追加</a>
    <x-book-table :$books />
  </main>
</body>
</html>