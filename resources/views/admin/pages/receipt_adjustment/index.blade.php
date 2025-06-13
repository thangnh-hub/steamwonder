@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Mã học sinh hoặc tên học sinh')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Type')</label>
                                <select name="type" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['type']) && $params['type'] == $key ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Tháng')</label>
                                <input type="month" name="month" class="form-control"
                                    value="{{ $params['month'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trạng thái')</label>
                                <select name="status" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            {{ __($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                {{-- <button class="btn btn-sm btn-success pull-right" data-toggle="modal"
                    data-target="#AdjustmentModal">@lang('Tính phí đối soát')</button> --}}
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
                                <th>@lang('Mã học sinh')</th>
                                <th>@lang('Tên học sinh')</th>
                                <th>@lang('TBP tương ứng')</th>
                                <th>@lang('Loại')</th>
                                <th>@lang('Số tiền')</th>
                                <th>@lang('Tháng')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px">{{ $row->student->student_code ?? '' }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->student->first_name ?? '' }}
                                        {{ $row->student->last_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->receipt->receipt_code ?? '' }}
                                        @if ($row->receipt_id != '')
                                            <a href="{{ route('receipt.show', $row->receipt_id) }}"
                                                onclick="return openCenteredPopup(this.href)"
                                                class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                    aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ __($row->type) }}
                                    </td>
                                    <td>
                                        {{ number_format($row->final_amount, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->month != '' ? date('m-Y', strtotime($row->month)) : '' }}
                                    </td>
                                    <td>
                                        {{ $row->note ?? '' }}
                                        @if ($row->receipt_id_old != '')
                                            <a href="{{ route('receipt.show', $row->receipt_id_old) }}"
                                                onclick="return openCenteredPopup(this.href)"
                                                class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                    aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ __($row->status) }}
                                    </td>
                                    <td>
                                        @if (!in_array($row->type, ['dunokytruoc', 'doisoat']))
                                            @if ($row->receipt_id !== null)
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="@lang('Update')" data-original-title="@lang('Update')"
                                                    href="{{ route('receipt.show', $row->receipt_id) }}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                            {{-- @else
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="@lang('Update')" data-original-title="@lang('Update')"
                                                    href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                                    method="POST" style="display:inline;"
                                                    onsubmit="return confirm('@lang('confirm_action')')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" type="submit">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form> --}}
                                            @endif
                                        @endif
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
    {{-- <div class="modal fade" id="AdjustmentModal" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12">@lang('Thêm đối soát')</h3>
                    </h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_area as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                            (Mã: {{ $value->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <select name="current_class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['current_class_id']) && $value->id == $params['current_class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tháng')</label>
                                <input type="month" name="month" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-remove"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

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
                        $('.show_detail_policies').html(response.data.view);
                        $('#modal_show_policies').modal('show');
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
