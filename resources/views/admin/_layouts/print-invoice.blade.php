<!DOCTYPE html>
<html lang="en" class="receipt-58">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
  <link rel="stylesheet" href="/assets/css/report.css">
  @vite([])
</head>

<body>
  <div class="wrapper">
    <section class="invoice">
      @yield('content')
    </section>
  </div>
  <script>
    window.addEventListener("load", window.print());
  </script>
</body>

</html>
