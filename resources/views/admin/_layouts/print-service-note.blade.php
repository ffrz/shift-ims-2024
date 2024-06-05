<!DOCTYPE html>
<html lang="en" class="page-a4">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
  <link rel="stylesheet" href="/assets/css/print.css">
  @vite([])
</head>

<body>
  @yield('content')
  <script>
    //window.addEventListener("load", window.print());
  </script>
</body>

</html>
