<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $title }}</title>
</head>
<body>
  <header>書籍管理システム<hr></header>
  <main>
    {{ $slot }}
  </main>
  <footer><hr>@Laravel</footer>
</body>
</html>