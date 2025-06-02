@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
                                            <h5>Thông tin thực phẩm <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Danh mục thực phẩm')<span class="text-danger">*</span></label>
                                                    <select required name="ingredient_category_id" class="form-control select2"style="width: 100%;">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($list_ingredient_categories as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ isset($detail->ingredient_category_id) && $detail->ingredient_category_id == $item->id ? 'selected' : '' }}>{{ __($item->name??"") }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name">@lang('Tên thực phẩm') <span class="text-danger">*</span></label>
                                                    <input placeholder="@lang('Tên thực phẩm')" type="text" name="name" class="form-control" value="{{ old('name', $detail->name ?? '') }}" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">@lang('Trạng thái')</label>
                                                    <select name="status" class="form-control select2">
                                                        @foreach($list_status as $key => $value)
                                                            <option value="{{ $key }}" {{ old('status', $detail->status ?? 1) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">@lang('Đơn vị chính')</label>
                                                    <select name="default_unit_id" class="form-control select2">">
                                                        @foreach($list_unit_id as $value)
                                                            <option value="{{ $value->id }}" {{ old('default_unit_id', $detail->default_unit_id ?? 1) == $value->id ? 'selected' : '' }}>{{ $value->name ?? "" }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="convert_to_gram">@lang('Chuyển sang g (hoặc ml)') </label>
                                                    <input placeholder="@lang('Chuyển sang g (hoặc ml)')" type="text" name="convert_to_gram" class="form-control" value="{{ old('convert_to_gram', $detail->convert_to_gram ?? '') }}" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Loại')</label>
                                                    <select name="type" class="form-control select2">
                                                        @foreach($list_type as $key => $value)
                                                            <option value="{{ $key }}" {{ old('type', $detail->type ?? 1) == $key ? 'selected' : '' }}>{{ __($value) }}</option>
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
                                    </div> <!-- tab-pane -->
                                </div> <!-- tab-content -->
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        
    </script>
@endsection
