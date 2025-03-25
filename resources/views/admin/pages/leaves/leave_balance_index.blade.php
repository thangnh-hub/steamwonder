@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection
@section('style')
    <style>
        b {
            margin-left: 10px;
        }

        li {
            list-style-type: circle;
            margin-left: 10px;
        }
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
        </h1>

    </section>
@endsection


@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form action="{{ route('leave.balance.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('leave.balance.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
                <div class="box_note">
                    <p><b>@lang('Ghi chú')</b></p>
                    <ul>
                        <li> Tổng phép năm sẽ tự động cập nhật theo tháng</li>
                        <li> Phép chuyển giao năm cũ sẽ lấy số phép còn lại của năm trước (Hiện chưa có dữ liệu)</li>
                        <li> Phép đã dùng là tổng số ngày nghỉ của đơn xin nghỉ (Loại nghỉ phép) "Đã được duyệt"</li>
                        <li> Phép khả dụng là số ngày phép có thể sử dựng ( = Tổng phép năm + Phép chuyển giao còn thời hạn
                            sử dụng)</li>
                    </ul>
                </div>
            </div>
            <div class="box-body table-responsive box-alert">
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

                @if (!isset($rows) || count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Năm')</th>
                                <th>@lang('Nhân viên')</th>
                                <th>@lang('Tổng phép năm')</th>
                                <th>@lang('Phép chuyển giao năm cũ')</th>
                                <th>@lang('Phép khả dụng')</th>
                                <th>@lang('Đã dùng')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $row->year ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->user->name ?? '' }}
                                    </td>
                                    <td>
                                        <div class="box_view view_total_leaves">
                                            {{ $row->total_leaves ?? 0 }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="total_leaves form-control" type="number" step="0.1"
                                                min="0" name="total_leaves" value="{{ $row->total_leaves ?? 0 }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view view_transfer_old">
                                            {{ $row->transfer_old ?? 0 }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="transfer_old form-control" type="number" step="0.5"
                                                min="0" name="transfer_old" value="{{ $row->transfer_old ?? 0 }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view view_available">
                                            {{ $row->available ?? 0 }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="available form-control" type="number" step="0.5"
                                                min="0" name="available" value="{{ $row->available ?? 0 }}">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="box_view view_used_leaves">
                                            {{ $row->used_leaves }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="used_leaves form-control" type="number" step="0.5"
                                                min="0" name="used_leaves" value="{{ $row->used_leaves ?? 0 }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view">
                                            <button class="btn btn-sm btn-warning btn_edit" data-toggle="tooltip"
                                                style="margin-right: 5px" title="@lang('Edit')"
                                                data-original-title="@lang('Edit')">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <div class="d-flex-wap">
                                                <button class="btn btn-sm btn-success btn_save mr-10" data-toggle="tooltip"
                                                    data-id="{{ $row->id }}"
                                                    data-original-title="@lang('Lưu')"><i class="fa fa-check"
                                                        aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-danger btn_exit" data-toggle="tooltip"
                                                    data-original-title="@lang('Hủy')"><i class="fa fa-times"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @if (isset($rows) && count($rows) > 0)
                <div class="box-footer clearfix">
                    <div class="row">
                        <div class="col-sm-5">
                            Tìm thấy {{ $rows->total() }} kết quả
                        </div>
                        <div class="col-sm-7">
                            {{ $rows->withQueryString()->links('admin.pagination.default') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script>
        $('.btn_edit').click(function() {
            var h = $(this).parents('tr').find('.box_view');
            var s = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        })
        $('.btn_exit').click(function() {
            var s = $(this).parents('tr').find('.box_view');
            var h = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        });
        $('.btn_save').click(function() {
            if (confirm('Bạn chắc chắn muốn lưu thông tin !')) {
                var _id = $(this).data('id');
                var url = "{{ route('leave.balance.update', ':id') }}".replace(':id', _id);
                // Lấy dữ liệu truyền ajax
                var total_leaves = $(this).parents('tr').find('.total_leaves').val();
                var transfer_old = $(this).parents('tr').find('.transfer_old').val();
                var available = $(this).parents('tr').find('.available').val();
                var used_leaves = $(this).parents('tr').find('.used_leaves').val();
                // View đổi nội dung
                var view_total_leave = $(this).parents('tr').find('.view_total_leave');
                var view_transfer_old = $(this).parents('tr').find('.view_transfer_old');
                var view_available = $(this).parents('tr').find('.view_available');
                var view_used_leaves = $(this).parents('tr').find('.view_used_leaves');
                // ẩn hiện
                var btn_exit = $(this).parents('tr').find('.btn_exit');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        total_leaves: total_leaves,
                        transfer_old: transfer_old,
                        available: available,
                        used_leaves: used_leaves,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'warning') {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            ` + response.message + `
                                        </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);

                            } else {
                                var _html = `<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    ` + response.message + `
                                </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);
                                // Cập nhật lại view
                                view_total_leave.html(response.data.total_leave);
                                view_transfer_old.html(response.data.transfer_old);
                                view_available.html(response.data.available);
                                view_used_leaves.html(response.data.used_leaves);
                                // view_quantity.html(response.data.quantity);
                            }

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
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                        btn_exit.click();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }

        })

        function show_hide(show, hide) {
            show.show();
            hide.hide();
        }
    </script>
@endsection
