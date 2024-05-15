<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="/dist/css/adminlte.min.css?v=3.2.0">
</head>

<body>
  <div class="wrapper">
    @yield('content')
    <section class="invoice">

    </section>

  </div>
  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
  <script>
    window.addEventListener("load", window.print());
  </script>
</body>

</html>
