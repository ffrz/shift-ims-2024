@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/dist/css/adminlte.min.css?v=3.2.0">
  <link rel="stylesheet" href="/assets/css/report.css">
  @vite([])
</head>

<body>
  <div class="wrapper">
    <section class="report">
      <div class="page">
        <h5 class="m-0">{{ Setting::value('app.business_name', 'Toko Saya') }}</h5>
        <div>{{ Setting::value('app.business_address', 'Jl. Sana Sini No. 123') }} -
          {{ Setting::value('app.business_phone', '081234567890') }}</div>
        <hr class="m-0">
        <h5 class="mt-2 mb-0">{{ $title }}</h5>
        @yield('content')
      </div>
    </section>
  </div>
  <script>
    //window.addEventListener("load", window.print());
  </script>
</body>

</html>
