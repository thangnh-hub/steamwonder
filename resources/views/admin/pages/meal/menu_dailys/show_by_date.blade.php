@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .content-header{
            display: flex;
            justify-content: space-between;
        }
        .box-body {
            margin-bottom: 0px;
        }
        .box {
            margin-bottom: 0px;
        }
        .box-header{
            background-color: #3c8dbc;
            color: white;
        }
        .list-group {
            margin-bottom: 5px !important;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
            <h1>
                @lang($module_name)
            </h1>
            <a class="pull-right" href="{{ route( 'mealmenu.daily.report') }}">
                <button type="button" class="btn btn-sm btn-success">@lang('Danh sách thống kê')</button>
            </a>
    </section>

    <!-- Main content -->
    <section class="content">
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

        @if(!empty($groupedIngredients))
            <div style="margin-bottom: 30px;" class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Tổng hợp tất cả nguyên liệu cho ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @foreach($groupedIngredients as $type => $items)
                        <label><strong>Loại: {{ ucfirst(__($type)) }}</strong></label>
                        <table class="table table-bordered " style="margin-bottom: 20px">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên nguyên liệu</th>
                                    <th>Định lượng tổng</th>
                                    <th>KG</th>
                                    <th>Đơn vị chính</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                    @php
                                        $ingredient = $item['ingredient'];
                                        $count = max($item['count_student'], 1);
                                        $valuePerOne = $item['total'] / $count;
                                        $valueInKg = $item['total'] / 1000;
                                        $defaultUnit = $ingredient->unitDefault->name ?? '';
                                        $convertedValue = null;
                                        if ($ingredient->convert_to_gram) {
                                            $ratio = $ingredient->convert_to_gram;
                                            $convertedValue = $ratio ? $item['total'] / $ratio : null;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ingredient->name }}</td>
                                        <td>{{ rtrim(rtrim(number_format($item['total'], 2, '.', ''), '0'), '.') }} g</td>
                                        <td>{{ rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.') }} kg</td>
                                        <td>
                                            @if($convertedValue)
                                                {{ rtrim(rtrim(number_format($convertedValue, 2, '.', ''), '0'), '.') }} {{ $defaultUnit }}
                                            @else
                                                {{ rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.') }} kg
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        @endif


        @foreach($menus as $menu)
            <div style="margin-bottom: 30px;" class="box collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('Nguyên liệu cho nhóm tuổi'): {{ $menu->mealAge->name ?? '-' }}
                        <span>({{ $menu->count_student ?? 0 }} suất)</span>
                    </h3>
                    
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body" >
                    @if($menu->menuIngredients->count())
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Tên nguyên liệu')</th>
                                    <th>@lang('Định lượng cho 1 suất')</th>
                                    <th>Định lượng tổng (x{{ $menu->count_student }} suất) g</th>
                                    <th>@lang('Tính theo KG')</th>
                                    <th>@lang('Tính theo đơn vị chính')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menu->menuIngredients as $item)
                                    @php
                                        $valuePerOne = $item->value / max($menu->count_student, 1);
                                        $ingredient = $item->ingredients;
                                        $defaultUnit = $ingredient->unitDefault->name ?? '';
                                        // Tính theo KG
                                        $valueInKg = $item->value / 1000;
                                        // Tính theo đơn vị chính
                                        $convertedValue = null;
                                        if ($ingredient->convert_to_gram) {
                                            $ratio = $ingredient->convert_to_gram ;
                                            $convertedValue = $ratio ? $item->value / $ratio : null;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->ingredients->name ?? '' }}</td>
                                        <td>
                                            {{ rtrim(rtrim(number_format($valuePerOne, 2, '.', ''), '0'), '.') }} g
                                        </td>
                                        <td>
                                            {{ rtrim(rtrim(number_format($item->value, 2), '0'), '.') }} g
                                        </td>
                                        <td>
                                            {{ rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.') }} kg
                                        </td>

                                        <td>
                                            @if($convertedValue)
                                            {{ rtrim(rtrim(number_format($convertedValue, 2, '.', ''), '0'), '.') }} {{ $defaultUnit }}
                                            @else
                                            {{ rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.') }} kg
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>@lang('Không có nguyên liệu nào được tính toán cho thực đơn này.')</p>
                    @endif
                </div>
            </div>
        @endforeach
    </section>
@endsection
