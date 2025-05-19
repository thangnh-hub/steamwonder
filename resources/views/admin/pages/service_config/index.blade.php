@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table-bordered>thead>tr>th {
            vertical-align: middle;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
                                <label>@lang('Loại phí')</label>
                                <select name="type" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['type']) && $params['type'] == $key ? 'selected' : '' }}>
                                            {{ __($item) }}</option>
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
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Loại phí dịch vụ')</th>
                                <th>@lang('Ngày bắt đầu áp dụng')</th>
                                <th>@lang('Ngày kết thúc áp dụng')</th>
                                <th class="text-center">@lang('Thời gian')</th>
                                <th>@lang('Ngày cập nhật')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ __($row->type) }}
                                    </td>

                                    <td>
                                        {{ $row->time_start != '' ? optional(\Carbon\Carbon::parse($row->time_start))->format('d/m/Y') : '' }}
                                    </td>

                                    <td>
                                        {{ $row->time_end != '' ? optional(\Carbon\Carbon::parse($row->time_end))->format('d/m/Y') : '' }}
                                    </td>
                                    <td >
                                        @if (isset($row->json_params->time_range) && count((array) $row->json_params->time_range) > 0)
                                            <ul>
                                                @foreach ((array) $row->json_params->time_range as $key => $val)
                                                    <li>
                                                        Từ:
                                                        {{ $val->block_start != '' ? optional(\Carbon\Carbon::parse($val->block_start))->format('H:i') : '' }}
                                                        - Đến:{{ $val->block_end != '' ? optional(\Carbon\Carbon::parse($val->block_end))->format('H:i') : '' }}
                                                        - Phí:{{ number_format($val->price, 0, ',', '.') ?? '' }} đ
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    <td>
                                        {{ date('H:i - d/m/Y', strtotime($row->updated_at)) }}
                                    </td>
                                    <td style="width:150px">
                                        <button class="btn btn-sm btn-success btn_show_detail" data-toggle="tooltip"
                                            data-id="{{ $row->id }}"
                                            data-url="{{ route(Request::segment(2) . '.show', $row->id) }}"
                                            title="@lang('Show')" data-original-title="@lang('Show')">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                                            data-original-title="@lang('Update')"
                                            href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                            <i class="fa fa-pencil-square-o"></i>
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
    <div class="modal fade" id="modal_show_service" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12">@lang('Thông tin chi tiết')</h3>
                    </h3>
                </div>
                <div class="modal-body show_detail_service">

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
                        $('.show_detail_service').html(response.data.view);
                        $('#modal_show_service').modal('show');
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
