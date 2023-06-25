<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $title }}</title>
</head>
<body>
  <header>
    書籍管理システム
    <form action="{{ route('admin.destroy') }}" method="POST">
      @csrf
      <input type="submit" value="ログアウト">
    </form>
    <hr>
  </header>
  <main>
    {{ $slot }}
  </main>
  <footer><hr>@Laravel</footer>
</body>
</html>