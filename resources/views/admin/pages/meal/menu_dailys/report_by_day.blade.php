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
            <form action="{{ route('mealmenu.daily.report') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="month" class="form-control" name="month" placeholder="@lang('Chọn tháng')"
                                    value="{{ isset($params['month']) ? $params['month'] :  \Carbon\Carbon::now()->format('Y-m') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select class="form-control select2" name="area_id">
                                    <option value="">@lang('Chọn')</option>
                                    @foreach($list_area as $area)
                                        <option value="{{ $area->id }}" {{ isset($params['area_id']) && $params['area_id'] == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
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
                                    <a class="btn btn-default btn-sm  mr-10" href="{{('report-meal-menu-daily') }}">
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
                @if (count($menusGrouped) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('STT')</th>
                            <th>@lang('Ngày')</th>
                            <th>@lang('Khu vực')</th>
                            <th>@lang('Nhóm trẻ')</th>
                            <th>@lang('Tên thực đơn theo nhóm')</th>
                            <th>@lang('Tổng số suất')</th>
                            <th>@lang('Thao tác')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menusGrouped as $date => $areas)
                            @foreach($areas as $areaId => $menus)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                    <td>{{ $menus->first()->area->name ?? '-' }}</td>
                                    <td>
                                        <ul>
                                            @foreach($menus as $menu)
                                                <li>{{ $menu->mealAge->name ?? '-' }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($menus as $menu)
                                                <li>
                                                    <a href="{{ route('menu_dailys.edit', $menu->id) }}"
                                                    onclick="return openCenteredPopup(this.href)">
                                                    {{ $menu->name ?? '-' }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $menus->sum('count_student') }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{ route('menu_dailys.showByDate', ['date' => $date, 'area_id' => $areaId]) }}">
                                            <i class="fa fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </section>

@endsection
