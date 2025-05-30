@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .modal-dialog.modal-custom {
            max-width: 80%;
            width: auto;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET" id="form_filter">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Mã hoặc tên TBP')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Học sinh')</label>
                                <select name="student_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($students as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['student_id']) && $params['student_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->student_code ?? '' }} - {{ $item->first_name ?? '' }}
                                            {{ $item->last_name ?? '' }}
                                            ({{ $item->nickname ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Ngày tạo')</label>
                                <input type="date" name="created_at" class="form-control"
                                    value="{{ $params['created_at'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                    <a href="javascript:void(0)" data-url="{{ route('receipt.export') }}"
                                        class="btn btn-sm btn-success btn_export">
                                        <i class="fa fa-file-excel-o"></i>
                                        @lang('Export dữ liệu')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>

            </div>
            <div class="box-body box_alert">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã TBP')</th>
                                <th>@lang('Tên TBP')</th>
                                <th>@lang('Học sinh')</th>
                                <th>@lang('Khu vực')</th>
                                {{-- <th>@lang('Chu kỳ thanh toán')</th> --}}
                                <th>@lang('Thành tiền')</th>
                                <th>@lang('Tổng giảm trừ')</th>
                                <th>@lang('Số dư kỳ trước')</th>
                                <th>@lang('Tổng tiền thực tế')</th>
                                <th>@lang('Đã thu')</th>
                                <th>@lang('Số tiền còn phải thu (+) hoặc thừa (-)')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Người tạo')</th>
                                <th>@lang('Ngày tạo')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        <strong style="font-size: 14px">{{ $row->receipt_code ?? '' }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->receipt_name }}
                                    </td>
                                    <td>
                                        {{ $row->student->student_code ?? '' }} - {{ $row->student->first_name ?? '' }}
                                        {{ $row->student->last_name ?? '' }} ({{ $row->student->nickname ?? '' }})
                                    </td>
                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    {{-- <td>
                                        {{ $row->payment_cycle->name ?? '' }}
                                    </td> --}}
                                    <td>
                                        {{ number_format($row->total_amount, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ number_format($row->total_discount, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ number_format($row->prev_balance, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ number_format($row->total_final, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ number_format($row->total_paid, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ number_format($row->total_due, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ __($row->status ?? '') }}
                                    </td>
                                    <td>
                                        {{ $row->note ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->adminCreated->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') ?? '' }}
                                    </td>
                                    <td class="">
                                        <a class="btn btn-sm btn-primary" href="{{ route('receipt.print', $row->id) }}"
                                            data-toggle="tooltip" title="@lang('In phiếu')"
                                            data-original-title="@lang('In phiếu')"
                                            onclick="return openCenteredPopup(this.href)">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success btn_show_detail" data-toggle="tooltip"
                                            data-id="{{ $row->id }}"
                                            data-url="{{ route(Request::segment(2) . '.view', $row->id) }}"
                                            title="@lang('Xem nhanh')" data-original-title="@lang('Xem nhanh')">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Chỉnh sửa')"
                                            data-original-title="@lang('Chỉnh sửa')" style="min-width: 34px"
                                            href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn_delete_receipt "
                                            data-id="{{ $row->id }}" data-toggle="tooltip"
                                            title="@lang('Xóa phiếu')" data-original-title="@lang('Xóa phiếu')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

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

        </div>
    </section>
    <div class="modal fade" id="modal_show_deduction" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12">@lang('Thông tin TBP')</h3>
                    </h3>
                </div>
                <div class="modal-body show_detail_deduction">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-remove"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.btn_show_detail').click(function() {
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    if (response) {
                        $('.show_detail_deduction').html(response.data.view);
                        $('#modal_show_deduction').modal('show');
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                    }

                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        });
        $('.btn_delete_receipt').click(function() {
            let currentStudentReceiptId = $(this).data('id'); // Lấy ID phiếu thu hiện tại từ nút
            if (confirm("Bạn có chắc chắn muốn xóa phiếu thu này?")) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('student.deleteReceipt') }}",
                    data: {
                        id: currentStudentReceiptId, // Đảm bảo đúng biến được gửi đi
                    },
                    success: function(response) {
                        if (response.message === 'success') {
                            localStorage.setItem('activeTab', '#tab_4');
                            location.reload();
                        } else {
                            alert("Bạn không có quyền thao tác dữ liệu");
                        }
                    },
                    error: function() {
                        alert("Lỗi cập nhật.");
                    }
                });
            }
        });

        $('.btn_export').click(function() {
            show_loading_notification();
            var formData = $('#form_filter').serialize();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                data: formData,
                success: function(response) {
                    if (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'Receipt.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        hide_loading_notification();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                        hide_loading_notification();
                    }
                },
                error: function(response) {
                    hide_loading_notification();
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })
    </script>
@endsection
