@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .box-body {
            width: 80%;
            margin: 0px auto;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)

        </h1>
    </section> --}}

    <!-- Main content -->
    <section class="content">
        <div class="box-alert">
            @if (session('errorMessage'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('errorMessage') }}
                </div>
            @endif
            @if (session('successMessage'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('successMessage') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            @lang($module_name)
                        </h3>
                        <a class="btn btn-primary pull-right hide-print" href="{{ route('leave.request.index') }}">
                            <i class="fa fa-bars"></i> @lang('Danh sách đơn')
                        </a>
                        @if ($detail->status == $status['pending_confirmation'] && $admin_auth->id == $detail->parent_id)
                            <button data-id="{{ $detail->id }}" type="button" data-type="parent"
                                class="btn btn-warning pull-right hide-print mr-10 approve_request">@lang('Xác nhận phiếu xin nghỉ')</button>
                        @endif
                        @if ($detail->status == $status['pending_approval'] && $admin_auth->id == $detail->approver_id)
                            <button data-id="{{ $detail->id }}" type="button" data-type="approve"
                                class="btn btn-success pull-right hide-print mr-10 approve_request">@lang('Duyệt phiếu xin nghỉ')</button>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom: 10px">@lang('Thông tin người tạo')</h4>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Họ tên'):
                                    {{ $balancy->user->name ?? '' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Năm'):
                                    {{ $balancy->year ?? 0 }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Tổng phép năm'):
                                    {{ $balancy->total_leaves ?? 0 }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Số phép chuyển giao'):
                                    {{ $balancy->transfer_old ?? 0 }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Số phép khả dụng'):
                                    {{ $balancy->available ?? 0 }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Số phép đã dùng'):
                                    {{ $balancy->used_leaves ?? 0 }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    @lang('Q.Lý trực tiếp'):
                                    {{ $detail->parent->name ?? '' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Người duyệt'): {{ $detail->approver->name ?? '' }}</p>
                            </div>

                            <div class="col-md-12 mt-15">
                                <h4 class="box-title" style="padding-bottom: 10px">@lang('Nội dung xin nghỉ')</h4>
                            </div>
                            <div class="col-md-6">

                                <p>
                                    @lang('Nghỉ từ ngày'):
                                    {{ \Carbon\Carbon::parse($detail->start_date)->format('d/m/Y') ?? '' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Đến ngày'):
                                    {{ \Carbon\Carbon::parse($detail->end_date)->format('d/m/Y') ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Số ngày nghỉ'): {{ $detail->total_days ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Loại'): {{ $detail->is_type == 'paid' ? 'Có phép' : 'Không phép' }}</p>
                            </div>

                            <div class="col-md-6">
                                <p>@lang('Trạng thái'): {{ __($detail->status) }}
                            </div>

                            <div class="col-md-6">
                                <p>@lang('Ngày dạy bù (Nếu có)'):
                                    {{ isset($detail->json_params->teaching_day) && count($detail->json_params->teaching_day) > 0
                                        ? implode(
                                            ' - ',
                                            array_map(function ($date) {
                                                return \Carbon\Carbon::parse($date)->format('d-m-Y');
                                            }, $detail->json_params->teaching_day),
                                        )
                                        : '' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Ngày tạo'):
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Lý do'): {{ $detail->reason ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>@lang('Ghi chú'): {{ $detail->note ?? '' }}</p>
                            </div>

                        </div>
                    </div>
                    <div class="box-footer hide-print">

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.approve_request').click(function() {
                if (confirm('Xác nhận duyệt đơn xin nghỉ ?')) {
                    var _id = $(this).data('id');
                    var _type = $(this).data('type');
                    let url = "{{ route('leave.request.approve') }}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: _id,
                            type: _type,
                        },
                        success: function(response) {
                            if (response.data != null) {
                                if (response.data == 'success') {
                                    location.reload();
                                } else {
                                    var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                        </div>`;
                                    $('.box-alert').prepend(_html);
                                    $('html, body').animate({
                                        scrollTop: $(".alert-warning").offset().top
                                    }, 1000);
                                    setTimeout(function() {
                                        $('.alert-warning').remove();
                                    }, 3000);
                                };
                            } else {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $('.alert-warning').remove();
                                }, 3000);
                            }
                        },
                        error: function(response) {
                            let errors = response.responseJSON.message;
                            alert(errors);
                        }
                    });
                }
            })
        });
    </script>
@endsection
