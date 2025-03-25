@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .hidden {
            display: none;
        }

        .mb-10 {
            margin-bottom: 10px
        }

        textarea {
            resize: none;
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
                            <h3 class="box-title">@lang('Tạo buổi thi')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Title') <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="title"
                                                    placeholder="@lang('Title')" value="{{ old('title') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Ngày thi') <small class="text-red">*</small></label>
                                                <input type="date" class="form-control" name="day_exam"
                                                    placeholder="@lang('Ngày thi')" value="{{ old('day_exam') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Thời gian thi') <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="time_exam"
                                                    placeholder="@lang('Thời gian thi (phút)')" value="{{ old('time_exam') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Thời gian bắt đầu') <small class="text-red">*</small></label>
                                                <input type="time" class="form-control" name="time_exam_start"
                                                    placeholder="@lang('Thời gian bắt đầu')" value="{{ old('time_exam_start') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Thời gian kết thúc') <small class="text-red">*</small></label>
                                                <input type="time" class="form-control" name="time_exam_end"
                                                    placeholder="@lang('Thời gian bắt đầu')" value="{{ old('time_exam_end') }}"
                                                    required>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Chọn khóa') <small class="text-red">*</small></label>
                                                <select required name="course_id"
                                                    class="form-control select2 select2-multy course_id">
                                                    <option value="">Chọn khóa</option>
                                                    @foreach ($course as $val)
                                                        <option value="{{ $val->id }}">
                                                            @lang($val->name ?? '')</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Chọn lớp thi') <small class="text-red">*</small></label>
                                                <select multiple name="list_class[]" required
                                                    class="form-control select2 select2-multy list_class">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Chọn hình thức')</label>
                                                <select name="type"
                                                    class="form-control select2 select2-multy">
                                                    @foreach ($type as $val)
                                                        <option value="{{ $val }}" >
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Chọn đề thi'):</label>
                                                @foreach ($list_topic as $topic)
                                                    <div class="d-flex ">
                                                        <input id="list_topic_{{ $topic->id }}" type="checkbox"
                                                            name="list_topic[]" value="{{ $topic->id }}" checked>
                                                        <label
                                                            for="list_topic_{{ $topic->id }}">{{ $topic->name ?? '' }}
                                                            ({{ $topic->question_exam ?? '' }} câu)</label>
                                                    </div>
                                                @endforeach
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
        $('.course_id').change(function() {
            var course_id = $(this).val();
            let url = "{{ route('exam_class_by_course') }}";
            let _targetHTML = $('.list_class');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    course_id: course_id,
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
    </script>
@endsection
