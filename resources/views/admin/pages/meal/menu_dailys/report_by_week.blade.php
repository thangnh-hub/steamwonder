@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
      
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            background-color: #fff;
            position: relative;
            max-height: 600px; /* hoặc theo nhu cầu */
            overflow-y: auto;
        }

        .card-header {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            padding: 10px 15px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
        }
       .area-block {
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .area-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 6px;
        }

        .age-block {
            margin-left: 10px;
            padding-left: 8px;
            border-left: 3px solid #ccc;
            margin-bottom: 6px;
        }

        .age-title {
            font-weight: bold;
            color: #006699;
            margin-bottom: 4px;
        }

        .meal-block {
            margin-left: 10px;
            padding-left: 10px;
            border-left: 2px dashed #aaa;
            margin-bottom: 4px;
        }

        .meal-title {
            font-weight: bold;
            color: #555;
            margin-bottom: 2px;
        }

        .dish-list {
            list-style: disc;
            padding-left: 20px;
            margin-bottom: 0;
        }

    </style>
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @if(!$show_report)
            @lang('Thống kê thực đơn theo tuần')
            @else
            @lang($module_name)
            @endif
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
        {{-- <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('mealmenu.week.report') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="week">Chọn tuần:</label>
                                <input type="week" name="week" id="week" class="form-control mx-2" value="{{ $week }}">
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
                                    <a class="btn btn-default btn-sm  mr-10" href="{{route('mealmenu.week.report') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div> --}}
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
               
                @if(!$show_report)
                    <form method="GET" action="{{ route('mealmenu.week.report') }}" class="form-inline mb-3">
                        <div class=" mr-2 box-center">
                            <select style="width:30%" name="year" id="year" class="form-control select2" onchange="this.form.submit()">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $selected_year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <br>
                    <div class="row">
                        @foreach($list_area as $area)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <strong>{{ $area->name }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach($currentYearWeeks as $week)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="{{ route('mealmenu.week.report', ['area_id' => $area->id, 'week' => $week['value'], 'year' => $selected_year]) }}">
                                                        {{ $week['label'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mb-3">
                        <a href="{{ route('mealmenu.week.report') }}" class="btn btn-primary">
                            ← Quay lại chọn tuần
                        </a>
                        <div class="pull-right" style="margin-bottom: 15px;">
                            <button id="btnViewByAge" class="btn btn-primary"><i class="fa fa-eye"></i> Hiển thị theo nhóm tuổi</button>
                            <button id="btnViewByDay" class="btn btn-default"><i class="fa fa-eye"></i> Hiển thị theo ngày</button>
                        </div>
                    </div>
                    <br>
                    <!-- View 1: Theo ngày -->
                    <div id="viewByDay" style="display:none;">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    @foreach($daysInWeek as $day)
                                        <th class="text-center">{{ Str::ucfirst(\Carbon\Carbon::parse($day)->translatedFormat('l d/m')) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($daysInWeek as $day)
                                        @php $dayStr = $day->format('Y-m-d'); @endphp
                                        <td style="vertical-align: top;">
                                            @if(isset($menusGrouped[$dayStr]))
                                                @foreach($menusGrouped[$dayStr] as $ageName => $meals)
                                                    <div class="age-block">
                                                        <div class="age-title"><strong>Nhóm tuổi:</strong> {{ $ageName }}</div>
                                                        @foreach($meals as $type => $dishes)
                                                            <div class="meal-block">
                                                                <div class="meal-title"><strong>{{ ucfirst(__($type)) }}</strong></div>
                                                                <ul class="dish-list">
                                                                    @foreach($dishes as $dish)
                                                                        <li>{{ $dish->name ?? '-' }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Không có thực đơn</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- View 2: Theo nhóm tuổi -->
                    <div id="viewByAge">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">Nhóm tuổi</th>
                                    <th rowspan="2" class="align-middle text-center">Bữa ăn</th>
                                    @foreach($daysInWeek as $day)
                                        <th class="text-center">{{ Str::ucfirst(\Carbon\Carbon::parse($day)->translatedFormat('l d/m')) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($menusGroupedByAge) == 0)
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @else    
                                @foreach($menusGroupedByAge as $ageName => $mealsByType)
                                    @php $firstRow = true; @endphp
                                    @foreach($dishesTime as $type => $label)
                                        <tr>
                                            @if($firstRow)
                                                <td rowspan="{{ count($dishesTime) }}" class="align-middle"><strong>{{ $ageName }}</strong></td>
                                                @php $firstRow = false; @endphp
                                            @endif
                                            <td><strong>{{ __($label) }}</strong></td>
                                            @foreach($daysInWeek as $day)
                                                @php
                                                    $dayStr = $day->format('Y-m-d');
                                                    $dishes = $mealsByType[$type][$dayStr] ?? [];
                                                @endphp
                                                <td>
                                                    @if(count($dishes))
                                                        <ul class="mb-0 pl-3">
                                                            @foreach($dishes as $dish)
                                                                <li>{{ $dish->name ?? '-' }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('#btnViewByDay').click(function(){
                $('#viewByDay').show();
                $('#viewByAge').hide();
                $(this).addClass('btn-primary').removeClass('btn-default');
                $('#btnViewByAge').removeClass('btn-primary').addClass('btn-default');
            });

            $('#btnViewByAge').click(function(){
                $('#viewByAge').show();
                $('#viewByDay').hide();
                $(this).addClass('btn-primary').removeClass('btn-default');
                $('#btnViewByDay').removeClass('btn-primary').addClass('btn-default');
            });
        });
    </script>
@endsection
