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
            <button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#createDailyMenuModal">
                <i class="fa fa-plus"></i> @lang('Add')
            </button>
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
                @if (count($menusGroupedByDate) == 0)
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
                            <th>@lang('Nhóm trẻ')</th>
                            <th>@lang('Tên thực đơn theo nhóm')</th>
                            <th>@lang('Tổng số suất')</th>
                            <th>@lang('Thao tác')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menusGroupedByDate as $date => $menus)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                <td>
                                    <ul>
                                        @foreach($menus as $menu)
                                        <li>
                                            {{ $menu->mealAge ? $menu->mealAge->name : '-' }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        @foreach($menus as $menu)
                                        <li>
                                            <a class="" href="{{ route('menu_dailys.edit',  $menu->id) }}"
                                                data-toggle="tooltip" title="@lang('Chi tiết thực đơn')"
                                                data-original-title="@lang('Chi tiết thực đơn')"
                                                onclick="return openCenteredPopup(this.href)">
                                                {{ $menu->name ?? '-' }} 
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    {{ $menus->sum('count_student') }}
                                </td>
                                <td>
                                    <a href="{{ route('menu_dailys.showByDate', ['date' => $date]) }}">Chi tiết</a>
                                </td>
                            </tr>
                            @endforeach
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
