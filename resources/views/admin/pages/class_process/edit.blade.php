@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .box_level {
            padding: 20px 0px;
            border-bottom: 2px dashed #3c8dbc ;
        }

        .text-white {
            color: #fff !important;
        }
        #alert-config{
            width: auto !important;
        }
    </style>
@endsection


@section('content')
<div id="alert-config"></div>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                            
                        </div>

                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                        
                                    </li>
                                </ul>
                                
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="class_name" placeholder="@lang('Title')"
                                                        value="{{ $detail->name ?? old('name') }}" required>
                                                    <p class="check-error text-danger"></p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Các buổi dự kiến') <small class="text-red">*</small></label>
                                                    <select disabled required class="form-control select2 select2-multy day_repeat" name="day_repeat[]" multiple>
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach (App\Consts::DAY_REPEAT as $key => $val)
                                                            <option value="{{ $key }}" {{ isset($detail->day_repeat) && in_array($key, json_decode($detail->day_repeat))?"selected":"" }}>
                                                                {{ $val }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            {{-- a11 --}}
                                            <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Trình độ A1.1')</label>
                                                        <button data-level="a11" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id" name="syllabus_a11"
                                                            required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_a11 as $val)
                                                                <option {{ isset($detail->a11->syllabus_id) && $detail->a11->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible" name="class_a11">
                                                            <option  value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date" class="form-control time_start" value="{{ isset($detail->a11->start_date)? $detail->a11->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->a11->end_date) ? $detail->a11->end_date : "" }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->a11->end_date_real) ? $detail->a11->end_date_real : "" }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- a12 --}}
                                            <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Trình độ A1.2')</label>
                                                        <button data-level="a12" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id" name="syllabus_a12"
                                                            required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_a12 as $val)
                                                                <option {{ isset($detail->a12->syllabus_id) && $detail->a12->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible" name="class_a12">
                                                            <option  value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date"  class="form-control time_start" value="{{ isset($detail->a12->start_date) ? $detail->a12->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->a12->end_date) ? $detail->a12->end_date : "" }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->a12->end_date_real) ? $detail->a12->end_date_real : "" }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- a21 --}}
                                            <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Trình độ A2.1')</label>
                                                        <button data-level="a21" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id"
                                                            name="syllabus_a21" required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_a21 as $val)
                                                                <option {{ isset($detail->a21->syllabus_id) && $detail->a21->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible"
                                                            name="class_a21" required>
                                                            <option value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date" class="form-control time_start" value="{{ isset($detail->a21->start_date) ? $detail->a21->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->a21->end_date) ? $detail->a21->end_date : "" }}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->a21->end_date_real) ? $detail->a21->end_date_real : "" }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- a22 --}}
                                            <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Trình độ A2.2')</label>
                                                        <button data-level="a22" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id"
                                                            name="syllabus_a22" required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_a22 as $val)
                                                                <option {{ isset($detail->a22->syllabus_id) && $detail->a22->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible"
                                                            name="class_a22" required>
                                                            <option value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date" class="form-control time_start" value="{{ isset($detail->a22->start_date) ? $detail->a22->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->a22->end_date) ? $detail->a22->end_date : "" }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->a22->end_date_real) ? $detail->a22->end_date_real : "" }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- b11 --}}
                                            <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Trình độ B1.1')</label>
                                                        <button data-level="b11" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id"
                                                            name="syllabus_b11" required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_b11 as $val)
                                                                <option {{ isset($detail->b11->syllabus_id) && $detail->b11->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible"
                                                            name="class_b11" required>
                                                            <option {{ isset($detail->b11->class_id) && $detail->b11->class_id==$val->id ? "selected" : "" }} value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date" class="form-control time_start" value="{{ isset($detail->b11->start_date) ? $detail->b11->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->b11->end_date) ? $detail->b11->end_date : "" }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->b11->end_date_real) ? $detail->b11->end_date_real : "" }}">
                                                    </div>
                                                </div>
                                            </div>


                                            {{-- b12 --}}
                                            <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Trình độ B1.2')</label>
                                                        <button data-level="b12" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id"
                                                            name="syllabus_b12" required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_b12 as $val)
                                                                <option {{ isset($detail->b12->syllabus_id) && $detail->b12->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible"
                                                            name="class_b12" required>
                                                            <option value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date" class="form-control time_start" value="{{ isset($detail->b12->start_date) ? $detail->b12->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->b12->end_date) ? $detail->b12->end_date : "" }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->b12->end_date_real) ? $detail->b12->end_date_real : "" }}" >
                                                    </div>
                                                </div>
                                            </div>

                                             {{-- OTCS --}}
                                             <div class="box_level col-md-12">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>@lang('Ôn thi chuyên sâu')</label>
                                                        <button data-level="otcs" type="button"
                                                            class="btn btn-info btn-sm form-control text-white save-process">
                                                            <i class="fa fa-save"></i> @lang('Save')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn chương trình') <small class="text-red">*</small></label>
                                                        <select class="form-control select2 syllabus_id"
                                                            name="syllabuss_otcs" required>
                                                            <option value="">@lang('Please select')</option>
                                                            @foreach ($syllabuss_otcs as $val)
                                                                <option {{ isset($detail->otcs->syllabus_id) && $detail->otcs->syllabus_id==$val->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Chọn lớp')</label>
                                                        <select class="form-control select2 class_avaible"
                                                            name="class_otcs" required>
                                                            <option value="">@lang('Please select')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu dự kiến') <small class="text-red">*</small></label>
                                                        <input type="date" class="form-control time_start" value="{{ isset($detail->otcs->start_date) ? $detail->otcs->start_date : date('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc dự kiến')</label>
                                                        <input type="date" class="form-control time_end" readonly value="{{ isset($detail->otcs->end_date) ? $detail->otcs->end_date : "" }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày kết thúc thực tế')</label>
                                                        <input type="date" class="form-control time_end_real" readonly value="{{ isset($detail->otcs->end_date_real) ? $detail->otcs->end_date_real : "" }}" >
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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
        $(document).ready(function () {
            $('.syllabus_id').trigger('change');
        })
        $('.syllabus_id').change(function() {
            var _process_id = "{{ $detail->id }}";
            var _level = $(this).parents('.box_level').find('.save-process').attr('data-level');
            var _id = $(this).val();
            let url = "{{ route('class_by_syllabus') }}";
            let _targetHTML = $(this).parents('.box_level').find('.class_avaible');
            $(this).parents('.box_level').find('.time_start').trigger('change');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: _id,
                    process_id: _process_id,
                    level: _level,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '<option value="">@lang('Please select')</option>';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<option '+item.selected+' {{ isset($detail->a11->class_id) && $detail->a11->class_id==$val->id ? "selected" : "" }} value="' + item.id + '">' + item
                                    .name + '</option>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<option value="">@lang('Không tìm thấy bản ghi phù hợp')</option>');
                    }
                    _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });

        })
        $('.time_start').change(function (e) { 
            var _process_id = "{{ $detail->id }}";
            var _syllabus_id =$(this).parents('.box_level').find('.syllabus_id').val();
            var _day_repeat = $('.day_repeat').val();
            var _start_date =$(this).val();

            let _targetHTML = $(this).parents('.box_level').find('.time_end');
            let url = "{{ route('calculator_time_end') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    process_id: _process_id,
                    start_date: _start_date,
                    day_repeat: _day_repeat,
                    syllabus_id: _syllabus_id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        _targetHTML.val(list);
                    }
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        });
        $('.save-process').click(function (e) { 
            var _process_id = "{{ $detail->id }}";
            var _level = $(this).attr('data-level');
            var _syllabus_id =$(this).parents('.box_level').find('.syllabus_id').val();
            var _class_id = $(this).parents('.box_level').find('.class_avaible').val();
            var _start_date =$(this).parents('.box_level').find('.time_start').val();
            var _end_date =$(this).parents('.box_level').find('.time_end').val();

            let _targetHTML = $(this).parents('.box_level').find('.time_end_real');
            let url = "{{ route('update_ajax_process') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    process_id: _process_id,
                    level: _level,
                    syllabus_id: _syllabus_id,
                    class_id: _class_id,
                    start_date: _start_date,
                    end_date: _end_date,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        if (list != '') _targetHTML.val(list);
                        $("#alert-config").append('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>');
                        setTimeout(function() {
                            $(".alert-success").fadeOut(2000, function() {});
                        }, 800);
                    }else{
                        $("#alert-config").append('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Vui lòng cập nhật trình độ trước</div>');
                        setTimeout(function() {
                            $(".alert-warning").fadeOut(2000, function() {});
                        }, 800);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = "@lang('CSRF token mismatch.')";
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    $("#alert-config").append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+elementErrors+'</div>');
                    setTimeout(function() {
                        $(".alert-danger").fadeOut(2000, function() {});
                    }, 800);
                }
            });
            
        });
    </script>
@endsection
