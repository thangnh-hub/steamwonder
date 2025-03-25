@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_product">
            @csrf
            @if (Request::get('lang') != '' && Request::get('lang') != $item->lang_locale)
                <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
            @endif
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>

                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>

                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name" id="class_name"
                                                        placeholder="@lang('Title')" value="{{ old('title') }}"
                                                        required>
                                                        <p class="check-error text-danger"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Trạng thái') <small class="text-red">*</small></label>
                                                    <select class="form-control select2" name="status"
                                                        required>
                                                        @foreach ($status_class as  $key=> $val)
                                                            <option value="{{ $key }}">
                                                                {{ $val}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Start date') <small class="text-red">*</small></label>
                                                    <input type="date" class="form-control" name="start_date"
                                                        placeholder="@lang('Start date')" value="{{ date('Y-m-d') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('End date') (Hủy)</label>
                                                    <input type="date" class="form-control" name="end_date" min="{{ date('Y-m-d') }}"
                                                        placeholder="@lang('End date')" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Class Type') <small class="text-red">*</small></label>
                                                    <select class="form-control select2" name="type">
                                                        @foreach (App\Consts::CLASS_TYPE as $key => $val)
                                                            <option value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Lớp thường/Lớp đặc biệt')</label>
                                                    <select name="type_normal_special" class="form-control select2">
                                                        @foreach (App\Consts::type_normal_special as $key => $val)
                                                            <option
                                                                {{ isset($detail->type_normal_special) && $detail->type_normal_special == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Teacher') <small class="text-red">*</small></label>
                                                    <select class="form-control select2" name="json_params[teacher]"
                                                        required>
                                                        <option value="">@lang('Teacher')</option>
                                                        @foreach ($teacher as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Period')<small class="text-red">*</small></label>
                                                    <select required name="period_id" class=" form-control select2">
                                                        <option value="">@lang('Period')</option>
                                                        @foreach ($period as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->iorder }} ({{ $val->start_time }} -
                                                                {{ $val->end_time }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Day repeat') <small class="text-red">*</small></label>
                                                    <select required class="form-control select2 select2-multy"
                                                        name="json_params[day_repeat][]" multiple>
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach (App\Consts::DAY_REPEAT as $key => $val)
                                                            <option value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Area')<small class="text-red">*</small></label>
                                                    <select required name="area_id" class="area_id form-control select2">
                                                        <option value="">@lang('Area')</option>
                                                        @foreach ($area as $val)
                                                            @if (in_array($val->id, $area_user))
                                                                <option value="{{ $val->id }}">
                                                                    {{ $val->name }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Room')<small class="text-red">*</small></label>
                                                    <select required name="room_id" class="room_id form-control select2">
                                                        <option value="">@lang('Room')</option>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->


                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Level') <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select required name="level_id" class="level_id form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($levels as $val)
                                        <option value="{{ $val->id }}">
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Syllabus') <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select required name="syllabus_id"
                                    class="syllabus_id syllabus_avaible form-control select2">
                                    <option value="">@lang('Please select')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Course') <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select required name="course_id" class="course_avaible form-control select2">
                                    <option value="">@lang('Course')</option>
                                    @foreach ($course as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($detail->course_id) && $detail->course_id == $val->id ? 'selected' : '' }}>
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Publish')</h3>
                        </div>
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        $('#class_name').on('blur', function () {
            var ten = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('ajax.nameclass.unique') }}',
                data: {ten: ten},
                success: function (response) {
                    console.log(response);
                    if (response.data == true) {
                        $('.check-error').text('Tên lớp đã tồn tại. Vui lòng chọn tên khác.');
                    }else $('.check-error').text('');
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        });
        $('.select2-multy').select2({
            placeholder: "@lang('Please select')",
        });
        $(document).ready(function() {
            $('.level_id').change(function() {
                var _id = $(this).val();
                let url = "{{ route('syllabus_by_level') }}";
                let _targetHTML = $('.syllabus_avaible');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: _id,
                        is_flag: 1,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            let _item = '<option value="">@lang('Please select')</option>';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<option value="' + item.id + '">' + item
                                        .name + '</option>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<option value="">@lang('Please select')</option>');
                        }
                        _targetHTML.trigger('change');
                    },
                    error: function(response) {
                        // Get errors
                        // let errors = response.responseJSON.message;
                        // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });

            })
            // $('.syllabus_id').change(function() {
            //     var syllabus_id = $(this).val();
            //     var level_id = $('.level_id').val();
            //     let url = "{{ route('course_by_syllabus') }}";
            //     let _targetHTML = $('.course_avaible');
            //     $.ajax({
            //         type: "POST",
            //         url: url,
            //         data: {
            //             "_token": "{{ csrf_token() }}",
            //             syllabus_id: syllabus_id,
            //             level_id: level_id,
            //         },
            //         success: function(response) {
            //             if (response.message == 'success') {
            //                 let list = response.data;
            //                 console.log(list);
            //                 let _item = '<option value="">@lang('Please select')</option>';
            //                 if (list.length > 0) {
            //                     list.forEach(item => {
            //                         _item += '<option value="' + item.id + '">' + item
            //                             .name + '</option>';
            //                     });
            //                     _targetHTML.html(_item);
            //                 }
            //             } else {
            //                 _targetHTML.html('<option value="">@lang('Please select')</option>');
            //             }
            //             _targetHTML.trigger('change');
            //         },
            //         error: function(response) {
            //             // Get errors
            //             // let errors = response.responseJSON.message;
            //             // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
            //         }
            //     });
            // })

            $('.area_id').change(function() {
                var area_id = $(this).val();
                let url = "{{ route('room_by_area') }}";
                let _targetHTML = $('.room_id');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        area_id: area_id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            console.log(list);
                            let _item = '<option value="">@lang('Room')</option>';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<option value="' + item.id + '">' + item
                                        .name + '</option>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<option value="">@lang('Room')</option>');
                        }
                        _targetHTML.trigger('change');
                    },
                    error: function(response) {
                        // Get errors
                        // let errors = response.responseJSON.message;
                        // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            })

        });
    </script>
@endsection
