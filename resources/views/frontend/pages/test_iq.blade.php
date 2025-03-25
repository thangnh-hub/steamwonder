@extends('frontend.layouts.test')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>Test IQ</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form action="{{ route('test_iq.student.post') }}" method="post" id="form_test_iq">
                @csrf
                @if (session('errorMessage'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>Thông báo:</h4>
                        {{ session('errorMessage') }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('successMessage') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="cccd">@lang('CCCD của học viên')</label>
                    <input type="text" id="cccd" name="cccd" required class="form-control" placeholder="Nhập CCCD">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-flat btn_submit">
                    @lang('Bắt đầu làm bài')
                </button>
            </form>
        </div>
    </div>
    @push('script')
    <script>
        sessionStorage.setItem('check_iq', 'off');
        $('.btn_submit').click(function(e) {
                e.preventDefault();
                sessionStorage.setItem('check_iq', 'on');
                $('#form_test_iq').submit();
            })
    </script>
@endpush
@endsection
