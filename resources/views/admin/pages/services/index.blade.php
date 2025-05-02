@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route($routeDefault . '.create') }}">
                <i class="fa fa-plus"></i> @lang('Add')
            </a>
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route($routeDefault . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword')</label>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="@lang('keyword_note')"
                                    value="{{ $params['keyword'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select name="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_area as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Nhóm dịch vụ')</label>
                                <select name="service_category_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_service_category as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['service_category_id']) && $params['service_category_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tính chất dịch vụ')</label>
                                <select name="is_attendance" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_is_attendance as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['is_attendance']) && $params['is_attendance'] == $key ? 'selected' : '' }}>
                                            {{ $item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Mặc định')</label>
                                <select name="is_default" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_is_default as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['is_default']) && $params['is_default'] == $key ? 'selected' : '' }}>
                                            {{ $item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại dịch vụ')</label>
                                <select name="service_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_service_type as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['service_type']) && $params['service_type'] == $key ? 'selected' : '' }}>
                                            {{ __($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            @lang($item)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div style="display:flex; gap:5px;">
                                    <button type="submit" class="btn btn-primary btn-sm">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route($routeDefault . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

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
                @if ($rows->count() === 0)
                    <div class="alert alert-warning">@lang('not_found')</div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Tên dịch vụ')</th>
                                <th>@lang('Nhóm dịch vụ')</th>
                                <th>@lang('Hệ đào tạo')</th>
                                <th>@lang('Độ tuổi')</th>
                                <th>@lang('Tính chất dịch vụ')</th>
                                <th>@lang('Loại dịch vụ')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Sắp xếp')</th>
                                <th>@lang('Biểu phí')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>{{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}</td>
                                    <td>{{ $row->area->name ?? "" }}</td>
                                    <td>{{ $row->name ?? "" }}</td>
                                    <td>{{ $row->service_category->name ?? "" }}</td>
                                    <td>{{ $row->education_program->name ?? "" }}</td>
                                    <td>{{ $row->education_age->name ?? "" }}</td>
                                    <td>{{ $row->is_attendance== 0 ? "Không theo điểm danh" : "Tính theo điểm danh" }}</td>
                                    <td>{{ __($row->service_type??"") }}</td>
                                    <td>@lang($row->status)</td>
                                    <td>
                                        {{ $row->iorder ?? "" }}
                                    </td>
                                    <td>
                                        @if(isset($row->serviceDetail) && $row->serviceDetail->count() > 0)
                                        @foreach ($row->serviceDetail as $detail)
                                        <ul>
                                            <li>Số tiền: {{ isset($detail->price) && is_numeric($detail->price) ? number_format($detail->price, 0, ',', '.') . ' đ' : '' }}</li>
                                            <li>Số lượng: {{ $detail->quantity ?? '' }}</li>
                                            <li>Từ: {{ (isset($detail->start_at) ? \Illuminate\Support\Carbon::parse($detail->start_at)->format('d-m-Y') : '') }}</li>
                                            <li>Đến: {{ (isset($detail->end_at) ? \Illuminate\Support\Carbon::parse($detail->end_at)->format('d-m-Y') : '') }}</li>
                                        </ul>
                                        @endforeach

                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="{{ route($routeDefault . '.edit', $row->id) }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <form action="{{ route($routeDefault . '.destroy', $row->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('@lang('confirm_action')')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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
        </div>
    </section>
@endsection
