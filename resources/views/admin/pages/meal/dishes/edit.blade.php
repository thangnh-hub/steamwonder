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
        .table-wrapper {
            max-height: 450px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }

        .table-wrapper table {
            border-collapse: separate;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
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
        <form action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Edit form')</h3>
                        </div>

                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin món ăn <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Nguyên liệu của món {{ $detail->name ?? "" }} <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">@lang('Tên món ăn') <span class="text-danger">*</span></label>
                                                    <input placeholder="@lang('Tên món ăn')" type="text" name="name" class="form-control" value="{{ old('name', $detail->name ?? '') }}" required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Loại món ăn')</label>
                                                    <select name="dishes_type" class="form-control select2">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach($list_type as $key => $value)
                                                            <option value="{{ $key }}" {{ isset($detail->dishes_type) && $detail->dishes_type == $key ? 'selected' : '' }}>{{ __($value) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Bữa ăn áp dụng')</label>
                                                    <select name="dishes_time" class="form-control select2">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach($list_time as $key => $value)
                                                            <option value="{{ $key }}" {{ isset($detail->dishes_time) && $detail->dishes_time == $key ? 'selected' : '' }}>{{ __($value) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="status">@lang('Trạng thái')</label>
                                                    <select name="status" class="form-control select2">
                                                        @foreach($list_status as $key => $value)
                                                            <option value="{{ $key }}" {{ old('status', $detail->status ?? 1) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">@lang('Mô tả')</label>
                                                    <textarea name="description" rows="5" class="form-control" placeholder="Mô tả">{{ $detail->description ?? "" }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title">@lang('Danh sách nguyên liệu')</h3>
                                                        <button type="button" class="btn btn-warning btn-sm pull-right " data-toggle="modal"
                                                            data-target="#addTPModal">
                                                            @lang('Thêm nguyên liệu')
                                                        </button>
                                                    </div>
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky">
                                                            <thead>
                                                                <tr>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2">@lang('STT')</th>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2">@lang('Mã thực phẩm')</th>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2">@lang('Tên thực phẩm')</th>
                                                                    <th colspan="{{ ($list_meal_age->count() ?? 0) }}" class="text-center">@lang('Định lượng (g)')</th>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2">@lang('Xóa')</th>
                                                                </tr>
                                                                <tr>
                                                                    @forelse ($list_meal_age as $age)
                                                                        <th class="text-center">{{ $age->name ?? "" }} </th>
                                                                    @empty
                                                                        <th class="text-center text-muted" colspan="1">@lang('Không có nhóm tuổi')</th>
                                                                    @endforelse
                                                                </tr>

                                                            </thead>
                                                            <tbody id="ingredient-list">
                                                                @foreach ($detail->quantitative as $ingredientId => $data)
                                                                    @php
                                                                        $ingredient = $list_ingredient->firstWhere('id', $ingredientId);
                                                                        if (!$ingredient) continue;
                                                                        $ingredientCode = 'TP' . str_pad($ingredient->id, 5, '0', STR_PAD_LEFT);
                                                                    @endphp
                                                                    <tr data-id="{{ $ingredientId }}">
                                                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                                                        <td class="text-center">{{ $ingredientCode }}</td>
                                                                        <td>{{ $ingredient->name }}</td>
                                                                        @foreach ($list_meal_age as $age)
                                                                            <td class="text-center">
                                                                                <input type="number" class="form-control"
                                                                                    name="json_params[quantitative][{{ $ingredientId }}][{{ $age->code }}]"
                                                                                    placeholder="Định lượng" step="any"
                                                                                    value="{{ $data[$age->code] }}">
                                                                            </td>
                                                                        @endforeach
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn btn-sm btn-danger btn-remove-ingredient">X</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                            </a>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>


    <div class="modal fade" id="addTPModal" tabindex="-1" role="dialog" aria-labelledby="addTPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTPModalLabel">@lang('Chọn nguyên liệu')</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Tìm theo tên thực phẩm')</label>
                                <input type="text" class="form-control" id="search-ingredient"
                                    placeholder="@lang('Từ khóa')">
                            </div>
                        </div>
                    </div>
                    <div class="table-wrapper table-responsive">
                        <table class="table table-hover table-bordered" id="ingredient-table">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>@lang('Tên thực phẩm')</th>
                                    <th>@lang('Mã thực phẩm')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_ingredient as $ingredient)
                                <tr class="ingredient-row" style="cursor: pointer;">
                                    <td>
                                        <input type="checkbox" name="parents[{{ $ingredient->id }}][id]"
                                            value="{{ $ingredient->id }}" >
                                    </td>
                                    <td class="ingredient-name">{{ $ingredient->name }} 
                                    <td class="ingredient-name">{{ 'TP' . str_pad($ingredient->id, 5, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirm-add-ingredient">@lang('Đồng ý')</button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">@lang('Đóng')</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).on('click', '.ingredient-row', function (e) {
            if ($(e.target).is('input[type="checkbox"]')) return;
            const checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', !checkbox.prop('checked'));
        });
        $('#search-ingredient').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#ingredient-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.ingredient-name').text().toLowerCase().indexOf(value) > -1);
            });
        });
        $(document).ready(function () {
            let stt = 1;

            $('#confirm-add-ingredient').on('click', function () {
                $('#ingredient-table input[type="checkbox"]:checked').each(function () {
                    const ingredientId = $(this).val();
                    const ingredientName = $(this).closest('tr').find('.ingredient-name').first().text().trim();
                    const ingredientCode = $(this).closest('tr').find('.ingredient-name').last().text().trim();

                    // chek đã tồn tại trong bảng list nguyên liệu chưa
                    if ($('#ingredient-list').find('tr[data-id="' + ingredientId + '"]').length === 0) {
                        const newRow = `
                            <tr data-id="${ingredientId}">
                                <td class="text-center">${stt++}</td>
                                <td class="text-center">${ingredientCode}</td>
                                <td>${ingredientName}</td>
                                
                                @foreach ($list_meal_age as $age)
                                    <td class="text-center">
                                        <input type="number" class="form-control"
                                            name="json_params[quantitative][${ingredientId}][{{ $age->code }}]"
                                            placeholder="Định lượng" value="0" step="any">
                                    </td>
                                @endforeach
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-remove-ingredient">X</button>
                                </td>
                            </tr>
                        `;

                        $('#ingredient-list').append(newRow);
                    }
                  
                });
                $('#addTPModal').modal('hide');
            });

            // xóa nguyên liệu
            $(document).on('click', '.btn-remove-ingredient', function () {
                $(this).closest('tr').remove();
                $('#ingredient-list tr').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });
                stt = $('#ingredient-list tr').length + 1;
            });
        });
        </script>

@endsection
