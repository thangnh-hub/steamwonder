@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .input_content {
            max-width: 100%;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
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
                            <h3 class="box-title">@lang('Tạo cấu hình phiên thi')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="d-flex-wap">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Trình độ') <small class="text-red">*</small></label>
                                                <select required name="id_level"
                                                    class="id_level form-control select2 w-100">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($levels as $val)
                                                        <option value="{{ $val->id ?? '' }}">
                                                            {{ $val->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Tổ chức')</label>
                                                <select name="organization" class="form-control select2 w-100">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($type as $val)
                                                        <option value="{{ $val }}">
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Chọn kỹ năng') <small class="text-red">*</small></label>
                                                <select required name="skill_test" class="form-control select2 w-100">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($skill as $val)
                                                        <option value="{{ $val }}">
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Thời gian thi') (Phút) <small class="text-red">*</small></label>
                                                <input required type="number" name="json_params[time]" class="form-control"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-10">
                                            <div class="form-group">
                                                <label>@lang('Nhóm phần thi, mô tả và số file') <small class="text-red">*</small></label>
                                                <div class="box_group mt-10">
                                                    <div class="d-flex-wap item_group mt-10 align-center">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>@lang('Chọn nhóm') <small
                                                                        class="text-red">*</small></label>
                                                                <select required class="form-control select2 w-100"
                                                                    onchange="add_name_input(this)">
                                                                    <option value="" hidden>@lang('Please choose')
                                                                    </option>
                                                                    @foreach ($arr_group as $val)
                                                                        <option value="{{ $val }}">
                                                                            @lang($val)</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Mô tả') <small
                                                                        class="text-red">*</small></label>
                                                                <textarea required type="text" name="" class="form-control input_content" value=""></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>@lang('Số file tương ứng') <small
                                                                        class="text-red">*</small></label>
                                                                <input required type="number" name=""
                                                                    class="form-control input_number" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>@lang('Mix')</label>
                                                                <div class="sw_featured d-flex-al-center">
                                                                    <label class="switch ">
                                                                        <input name="" class="input_mix"
                                                                            value="1" type="checkbox">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="btn btn-sm btn-danger"
                                                                onclick="delete_group(this)">
                                                                Xóa</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button"
                                                    class="btn btn-sm btn-primary add_group">@lang('Thêm nhóm câu hỏi')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script>
        var arr_group = @json($arr_group ?? []);
        $('.add_group').click(function() {
            let options = `<option value="">@lang('Please choose')</option>`;
            arr_group.forEach(val => {
                options += `<option value="${val}">@lang('${val}')</option>`;
            });
            let _html = `
                <div class="d-flex-wap item_group mt-10 align-center">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Chọn nhóm') <small class="text-red">*</small></label>
                            <select required class="form-control select2 w-100"  onchange="add_name_input(this)">
                                ${options}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Mô tả') <small
                                    class="text-red">*</small></label>
                           <textarea required type="text" name=""
                            class="form-control input_content" value=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>@lang('Số file tương ứng') <small class="text-red">*</small></label>
                            <input required type="number" name="" class="form-control input_number" value="">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>@lang('Mix')</label>
                            <div class="sw_featured d-flex-al-center">
                                <label class="switch ">
                                    <input class="input_mix" name=""
                                        value="1" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn btn-sm btn-danger" onclick="delete_group(this)">Xóa</div>
                    </div>
                </div>
            `;

            // Thêm vào DOM và khởi tạo lại select2
            $('.box_group').append(_html);
            $('.select2').select2();
        });


        function delete_group(th) {
            $(th).parents('.item_group').remove();
        }

        function add_name_input(th) {
            let _type = $(th).val();
            let _name = `topic[${_type}]`;
            $(th).closest('.item_group')
                .find('.input_number')
                .attr('name', _name + '[file]').end()
                .find('.input_content')
                .attr('name', _name + '[content]').end()
                .find('.input_mix')
                .attr('name', _name + '[mix]');
        }
    </script>
@endsection
