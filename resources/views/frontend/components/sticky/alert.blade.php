@if (session('errorMessage'))
    <script>
        Swal.fire({
            toast: true,
            icon: 'error',
            title: '{{ session('errorMessage') }}',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
@endif
@if (session('successMessage'))
    <script>
        Swal.fire({
            toast: true,
            icon: 'success',
            title: '{{ session('successMessage') }}',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
@endif
@if (session('warningMessage'))
    <script>
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: '{{ session('warningMessage') }}',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
@endif
@if (session('successMessageCart'))
    <script>
        Swal.fire({
            toast: true,
            icon: 'error',
            title: '{{ session('successMessageCart') }}',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            Swal.fire({
                toast: true,
                icon: 'error',
                title: '{{ $error }}',
                animation: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            })
        </script>
    @endforeach
@endif
<div class="content_alert"></div>
<style>
    .alert-fixed {
        position: fixed;
        top: 0px;
        right: 0px;
        margin: 1rem;
        z-index: 999999;
    }
</style>
