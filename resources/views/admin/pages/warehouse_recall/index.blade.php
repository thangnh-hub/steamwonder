@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
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
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default hide-print">

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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên phiếu đề xuất, mã phiếu..')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kho')</label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_warehouse as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kỳ')</label>
                                <input class="form-control" type="month" name="period" value="{{ isset($params['period']) ? $params['period'] : '' }}">
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
            </div>
            <div class="box-body table-responsive">
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
                    <table class="table table-hover table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Kho')</th>
                                <th>@lang('Mã phiếu')</th>
                                <th>@lang('Tên phiếu đề xuất')</th>
                                <th>@lang('Số sản phẩm')</th>
                                <th>@lang('Tổng số tài sản')</th>
                                <th>@lang('Ngày đề xuất')</th>
                                <th>@lang('Kỳ')</th>
                                <th>@lang('Ghi chú')</th>
                                <th class="hide-print">@lang('Chức năng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>

                                        <td>
                                            {{ $row->warehouse->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->code ?? '' }}
                                        </td>
                                        <td>
                                             {{ $row->name ?? '' }}
                                        </td>
                                        <td>
                                             {{ $row->entryDetails->count() ?? '' }}
                                        </td>
                                        <td>
                                             {{ $row->entryDetails->sum('quantity') ?? '' }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y',strtotime($row->day_create))}}
                                        </td>
                                        <td>
                                            {{ $row->period ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->note ?? '' }}
                                        </td>

                                        <td class="hide-print">
                                            <a class="btn btn-sm btn-info" data-toggle="tooltip"
                                                title="@lang('Chi tiết')" data-original-title="@lang('Chi tiết')"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix hide-print">
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
@endsection
@section('script')
@endsection
