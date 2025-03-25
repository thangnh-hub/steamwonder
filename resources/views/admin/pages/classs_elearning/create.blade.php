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
                <div class="col-lg-12">
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
                                                    <label>@lang('Class Type') <small class="text-red">*</small></label>
                                                    <select class="form-control select2" name="type">
                                                        @foreach (App\Consts::CLASS_TYPE as $key => $val)
                                                            <option {{ $key=="elearning"?"selected":"" }} value="{{ $key }}">
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
                                                    <label>@lang('Level')<small class="text-red">*</small></label>
                                                    <select required name="level_id" class="level_id form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($levels as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Syllabus')<small class="text-red">*</small></label>
                                                    <select required name="syllabus_id"
                                                        class="syllabus_id syllabus_avaible form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Course')<small class="text-red">*</small></label>
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
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->


                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.level_id').change(function() {
                var _id = $(this).val();
                let url = "{{ route('syllabus_online_by_level') }}";
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
        });
    </script>
@endsection
