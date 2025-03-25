<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Test IQ | Authentication</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="{{ asset('themes/admin/img/meta-logo-favicon.png') }}">

    {{-- Include style for app --}}
    @include('admin.panels/styles')
    @stack('style')


</head>

<body class="hold-transition login-page">

    @yield('content')

    {{-- Include scripts --}}
    @include('admin.panels.scripts')

    @stack('script')
    <script>
        $(document).ready(function() {
            $('#form_test_iq').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>

</html>
