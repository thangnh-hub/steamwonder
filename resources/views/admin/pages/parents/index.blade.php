@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
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
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2"style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>{{ __($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm  mr-10" href="{{ route(Request::segment(2) . '.index') }}">
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
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('STT')</th>
                            <th>@lang('Avatar')</th>
                            <th>@lang('Họ và tên')</th>
                            <th>@lang('Giới tính')</th>
                            <th>@lang('Ngày sinh')</th>
                            <th>@lang('Số CMND/CCCD')</th>
                            <th>@lang('Số điện thoại')</th>
                            <th>@lang('Email')</th>  
                            <th>@lang('Địa chỉ')</th>
                            <th>@lang('Khu vực')</th>
                            <th>@lang('Trạng thái')</th>
                            <th>@lang('Thao tác')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="valign-middle">
                                <td>
                                    {{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}
                                </td>
                                <td>
                                    @if (!empty($row->avatar))
                                        <img src="{{ asset($row->avatar) }}" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $row->first_name ?? '' }} {{ $row->last_name ?? '' }}</td>
                                <td>
                                    @lang($row->sex ?? '')
                                </td>
                                <td>{{ \Carbon\Carbon::parse($row->birthday)->format('d/m/Y') ?? '' }}</td>
                                <td>{{ $row->identity_card ?? '' }}</td>
                                <td>{{ $row->phone ?? '' }}</td>
                                <td>{{ $row->email ?? '' }}</td>
                                <td>{{ $row->address ?? '' }}</td>
                                <td>{{ $row->area->name ?? '' }}</td>
                                <td>@lang($row->status)</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                                       href="{{ route('parents.edit', $row->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                
                                    <form action="{{ route('parents.destroy', $row->id) }}" method="POST"
                                          style="display:inline-block"
                                          onsubmit="return confirm('@lang('confirm_action')')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                
                                    <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="@lang('Chi tiết')"
                                       href="{{ route('parents.show', $row->id) }}">
                                        <i class="fa fa-eye"></i> Chi tiết
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

@endsection
@section('script')
    <script>
        $(document).ready(function() {
           
        });
    </script>
@endsection
