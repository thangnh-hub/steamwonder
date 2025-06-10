@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .modal-header {
            background-color: #3c8dbc;
            color: white;
        }
        th {
            text-align: center;
            vertical-align: middle !important;
        }   
    </style>
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
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
                                <label>@lang('Keyword')</label>
                                <input type="text" name="keyword" class="form-control"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}"
                                    placeholder="@lang('Tên thực phẩm')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select multiple name="area_id[]" class="form-control select2"style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_area as $key => $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && in_array($item->id,$params['area_id']) ? 'selected' : '' }}>{{ __($item->name) }}
                                        </option>
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
                @if (count($ingredients_data) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center align-middle">
                            <th rowspan="2">@lang('STT')</th>
                            <th rowspan="2">@lang('Thực phẩm')</th>
                            <th colspan="{{ $areas_from_rows->count() }}">@lang('Cơ sở')</th>
                            <th rowspan="2">@lang('Ghi chú')</th>
                        </tr>
                        <tr class="text-center">
                            @foreach($areas_from_rows as $area)
                                <th>{{ $area->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ingredients_data as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item['ingredient_name'] }}</td>
                                @foreach($areas_from_rows as $area)
                                    <td class="text-end">{{ $item['area_quantities'][$area->id] ?? "" }}</td>
                                @endforeach
                                <td>{{ $item['note'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 5 + $areas_from_rows->count() }}" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
       
    </script>
@endsection
