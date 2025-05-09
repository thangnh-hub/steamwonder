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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Mã hoặc tên chu kỳ')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2 w-100">
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
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
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
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px">{{ $row->receipt_code ?? '' }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->receipt_name }}
                                    </td>
                                    <td>
                                        {{ $row->student->student_code ?? ('' . ' - ' . $row->student->first_name ?? ('' . ' ' . $row->student->last_name ?? '')) }}({{ $row->student->nickname }})
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
                                        {{ __($row->status??'') }}
                                    </td>
                                    <td>
                                        {{ $row->note ?? '' }}
                                    </td>
                                    <td class="d-flex-wap">

                                        <button class="btn btn-sm btn-success btn_show_detail mr-10" data-toggle="tooltip"
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
        })
    </script>
@endsection
