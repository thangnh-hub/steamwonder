<html>

<head>
  <title>
    @yield('title') | DWN
  </title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
    }

    table.content {
      width: 100%;
      border-collapse: collapse;
      border-spacing: 0;
      padding: 0px;
      margin: 0px;
      font-size: 11px;
      line-height: 1.4;
      border: 1px solid #000000;
    }

    .content th {
      border: solid 1px #000000;
      text-align: left;
    }

    .content .row td {
      border: solid 1px #000000;
    }

    .table-container {
      page-break-inside: avoid;
    }

    tr {
      page-break-inside: avoid;
    }

    td[rowspan] {
      page-break-before: always;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    @yield('content')

  </div>
</body>

</html>
