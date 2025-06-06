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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin thực đơn<span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>
        
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
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
                                                    <label>@lang('Nhóm tuổi áp dụng') <span class="text-danger">*</span></label>
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
                                                    <label for="count_student">@lang('Số lượng học sinh') <span class="text-danger">*</span></label>
                                                    <input required type="number" name="count_student" class="form-control" value="{{ old('count_student', $detail->count_student ?? '') }}" min="0">
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
