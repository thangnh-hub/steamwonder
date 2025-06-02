@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')

    <style>
        .modal-sm {
            width: 30%;
        }
        .modal-footer{
            text-align: left !important;
        }
        .list-group {
            margin-bottom: 5px !important;
        }
        .modal-header {
            background-color: #3c8dbc;
            color: white;
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
        <div class="row">
            <div class="col-lg-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Món ăn')</h3>
                        <button type="button" class="btn btn-warning btn-sm pull-right" 
                                data-toggle="modal" data-target="#addDishModal">
                                <i class="fa fa-plus"></i> Thêm món vào thực đơn
                        </button>
                    </div>
                    
                    <div class="box-body">
                        <div class="form-group">
                            @foreach($mealTypes as $key => $label)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>{{ $label }}</strong>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            @foreach($dishes_by_type[$key] ?? [] as $dish)
                                                <li class="list-group-item clearfix">
                                                    {{ $loop->iteration }}. {{ $dish->dishes->name }}
                                                    <div class="pull-right">
                                                        <button type="button" class="btn btn-xs btn-default btn-move-dish" 
                                                                data-toggle="modal" data-target="#exchangeDishes"
                                                                data-dish-id="{{ $dish->id }}" 
                                                                data-dish-name="{{ $dish->dishes->name }}"
                                                                title="Di chuyển sang bữa khác">
                                                            <i class="fa fa-exchange"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-xs btn-danger btn-delete-dish"
                                                                data-toggle="modal" data-target="#deleteDishModal"
                                                                data-dish-id="{{ $dish->id }}"
                                                                data-dish-name="{{ $dish->dishes->name }}"
                                                                title="Xóa món ăn">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach

                                            @if(empty($dishes_by_type[$key]) || count($dishes_by_type[$key]) == 0)
                                                <li class="list-group-item text-muted">Không có món ăn</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-9">
                <form action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Cập nhật thông tin thực đơn')</h3>
                        </div>
                        
                        <div class="box-body">
                            <div class="d-flex-wap">
                                {{-- Tên thực đơn --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">@lang('Tên thực đơn') <span class="text-danger">*</span></label>
                                        <input placeholder="@lang('Tên thực đơn')" type="text" name="name" class="form-control" value="{{ old('name', $detail->name ?? '') }}" required>
                                    </div>
                                </div>

                                {{-- Độ tuổi áp dụng --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Nhóm đối tượng') <span class="text-danger">*</span></label>
                                        <select name="meal_age_id" class="form-control select2" required>
                                            <option value="">@lang('Chọn')</option>
                                            @foreach($list_meal_age as $item)
                                                <option value="{{ $item->id }}" {{ (old('meal_age_id', $detail->meal_age_id ?? '') == $item->id) ? 'selected' : '' }}>{{ $item->name ?? "" }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Số lượng học sinh --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="count_student">@lang('Số lượng học sinh')</label>
                                        <input type="number" name="count_student" class="form-control" value="{{ old('count_student', $detail->count_student ?? '') }}" min="0">
                                    </div>
                                </div>

                                {{-- Mùa áp dụng --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Mùa áp dụng')</label>
                                        <select name="season" class="form-control select2">
                                            <option value="">@lang('Chọn')</option>
                                            @foreach($list_season as $key => $value)
                                                <option value="{{ $key }}" {{ (old('season', $detail->season ?? '') == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Trạng thái --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">@lang('Trạng thái')</label>
                                        <select name="status" class="form-control select2">
                                            @foreach($list_status as $key => $value)
                                                <option value="{{ $key }}" {{ old('status', $detail->status ?? 1) == $key ? 'selected' : '' }}>{{ __($value) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Mô tả --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">@lang('Mô tả')</label>
                                        <textarea name="description" rows="4" class="form-control" placeholder="@lang('Nhập mô tả')">{{ old('description', $detail->description ?? '') }}</textarea>
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
                </form>

                <form action="{{ route('admin.meal_menu.updateIngredients', $detail->id) }}" method="POST">
                @csrf

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Thực phẩm trong thực đơn')</h3>
                    </div>

                    <div class="box-body">
                        @if($detail->menuIngredients->count())
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên nguyên liệu</th>
                                        <th>Định lượng cho 1 người</th>
                                        <th>Định lượng tổng (x {{ $detail->count_student }} người)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detail->menuIngredients as $item)
                                        @php
                                            $valuePerOne = $item->value / max($detail->count_student, 1);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->ingredients->name ?? '' }}</td>
                                            <td>
                                                <input 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0" 
                                                    name="ingredients[{{ $item->id }}]" 
                                                    class="form-control input-per-one" 
                                                    data-id="{{ $item->id }}" 
                                                    value="{{ number_format($valuePerOne, 2, '.', '') }}">
                                            </td>
                                            <td>
                                                <span class="total-value" id="total-{{ $item->id }}">
                                                    {{ number_format($item->value, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Không có nguyên liệu nào được tính toán cho thực đơn này.</p>
                        @endif
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-info btn-sm pull-right">
                            <i class="fa fa-save"></i> @lang('Save')
                        </button>
                    </div>
                </div>
            </form>

            </div>
        </div>
    </section>

    {{-- di chuyển --}}
    <div class="modal fade" id="exchangeDishes" tabindex="-1" role="dialog" aria-labelledby="exchangeDishesLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form method="POST" action="{{ route('mealmenu.moveDish') }}" id="moveDishForm">
                @csrf
                <input type="hidden" name="dish_id" id="modal_dish_id" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exchangeDishesLabel">Chuyển món ăn</h5>
                    </div>
                    <div class="modal-body">
                        <p>Bạn đang chuyển món: <strong id="modal_dish_name"></strong></p>
                        <div class="form-group">
                            <label for="new_meal_type">Chọn bữa khác</label>
                            <select style="width:100%" class="form-control select2" name="new_meal_type" id="new_meal_type" required>
                                <option value="">Chọn bữa</option>
                                @foreach($mealTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Đồng ý</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Xóa món ăn --}}
    <div class="modal fade" id="deleteDishModal" tabindex="-1" role="dialog" aria-labelledby="deleteDishModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form method="POST" action="{{ route('mealmenu.deleteDish') }}" id="deleteDishForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="dish_id" id="delete_dish_id" value="">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h4 class="modal-title">
                            <i class="fa fa-exclamation-triangle text-warning"></i> Xác nhận xoá
                        </h4>
                    </div>
                    <div class="modal-body text-center">
                        <p >
                            Bạn có chắc chắn muốn xoá món:<br>
                            <strong id="delete_dish_name" class="text-bold"></strong> ?
                        </p>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Xoá
                        </button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i> Huỷ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Thêm món ăn --}}
    <div class="modal fade" id="addDishModal" tabindex="-1" role="dialog" aria-labelledby="addDishModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('mealmenu.addDishes') }}" id="addDishForm">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $detail->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm món ăn vào thực đơn</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bữa ăn: </label>
                                    <select style="width:100%" name="type" id="dish_type" class="form-control select2" required>
                                        <option value="">-- Chọn bữa --</option>
                                        @foreach($mealTypes as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Từ khóa'): </label>
                                    <input type="text" id="searchKeyword" class="form-control ml-2" placeholder="Tìm món ăn...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Filter')</label>
                                    <div style="display:flex;jsutify-content:space-between;">
                                        <button  type="button" id="btnSearchDishes" class="btn btn-primary ">
                                            <i class="fa fa-search"></i> Tìm
                                        </button>
                                        <span id="loading-spinner" style="display: none;">
                                            <i class="fa fa-spinner fa-spin text-info"></i> Đang tìm kiếm...
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover" id="ingredient-table">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>Tên món ăn</th>
                                    <th>Mã món</th>
                                </tr>
                            </thead>
                            <tbody id="dishesResult">
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Thêm món</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('.btn-move-dish').click(function(){
                let dishId = $(this).data('dish-id');
                let dishName = $(this).data('dish-name');
                $('#modal_dish_id').val(dishId);
                $('#modal_dish_name').text(dishName);
                $('#new_meal_type').val('');
            });

            $('.btn-delete-dish').on('click', function () {
                var dishId = $(this).data('dish-id');
                var dishName = $(this).data('dish-name');

                $('#delete_dish_id').val(dishId);
                $('#delete_dish_name').text(dishName);
            });
        });

        //Ajax search for dishes
        $('#btnSearchDishes').click(function () {
            var keyword = $('#searchKeyword').val();
            var type = $('#dish_type').val();

            if (!type) {
                alert('Vui lòng chọn bữa ăn trước.');
                return;
            }

            $('#loading-spinner').show();
            $('#dishesResult').html('');
            $('#addDishForm button[type="submit"]').prop('disabled', true); // Disable submit

            $.ajax({
                url: '{{ route("mealmenu.searchDishes") }}',
                method: 'GET',
                data: { keyword: keyword },
                success: function (data) {
                    $('#loading-spinner').hide();
                    if (data.length === 0) {
                        alert('Không tìm thấy món ăn nào.');
                        return;
                    }
                    let html = '';
                    data.forEach(function (dish) {
                        html += `
                            <tr>
                                <td><input type="checkbox" class="dish-checkbox" name="dishes_ids[]" value="${dish.id}"></td>
                                <td>${dish.name}</td>
                                <td>${'MA' + String(dish.id).padStart(5, '0')}</td>
                            </tr>
                        `;
                    });
                    $('#dishesResult').html(html);
                },
                error: function () {
                    $('#loading-spinner').hide();
                    alert('Lỗi khi tìm kiếm.');
                }
            });
        });
        $(document).on('change', '.dish-checkbox', function () {
            let anyChecked = $('.dish-checkbox:checked').length > 0;
            $('#addDishForm button[type="submit"]').prop('disabled', !anyChecked);
        });
        $('#addDishForm button[type="submit"]').prop('disabled', true);
        
        // Cập nhật giá trị tổng khi thay đổi định lượng cho 1 người
         $(document).ready(function () {
            const countStudent = {{ $detail->count_student }};

            $('.input-per-one').on('input', function () {
                const id = $(this).data('id');
                const perOne = parseFloat($(this).val()) || 0;
                const total = (perOne * countStudent).toFixed(2);

                $('#total-' + id).text(total);
            });
        });

    </script>
    
@endsection
