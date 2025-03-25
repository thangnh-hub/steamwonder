<!DOCTYPE html>
<html lang="{{ $locale ?? 'vi' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style>
    * {
      font-size: 14px;
    }

    .container h1 {
      font-weight: 300;
      margin-top: 0;
      font-size: 24px;
    }

  </style>

  @yield('style')

</head>

<body style="background:#fff;font-family:'Roboto';">
  @yield('content')
</body>

</html>
