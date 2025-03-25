@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
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
                                                    <label>@lang('Title')</label>
                                                    <input type="text" class="form-control job_title" name="job_title"
                                                        placeholder="@lang('Title')" value="{{ old('job_title') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã chương trình')</label>
                                                    <input onchange="getValueTitle()" type="text"
                                                        class="form-control syllasbuss_code" name="job_code"
                                                        placeholder="@lang('Mã chương trình')" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Chọn đối tác') </label>
                                                    <select onchange="getparnerCode()"
                                                        class="form-control select2 select_partner_code">
                                                        <option value="">Chọn đối tác</option>
                                                        @foreach ($partner as $key => $val)
                                                            <option value="{{ $val->user_code }}"
                                                                {{ isset($detail->partner_code) && $detail->partner_code == $val->user_code ? 'checked' : '' }}>
                                                                @lang($val->name ?? '')</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Mã đối tác') <small class="text-red">*</small></label>
                                                    <input name="partner_code" type="text" onchange="getSyllabusCode()"
                                                        class="form-control partner_code" name="partner_code"
                                                        placeholder="@lang('Mã chương trình')" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số chương trình')</label>
                                                    <input onchange="getSyllabusCode()" type="number"
                                                        class="form-control syllabus_quantity" name="maijor_quantity"
                                                        placeholder="@lang('Số chương trình')" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Chương trình học') <small class="text-red">*</small></label>
                                                    <select required name="maijor_code" class=" form-control select2">
                                                        <option value="">Chọn chương trình học</option>
                                                        @foreach ($major as $key => $val)
                                                            <option value="{{ $val->code }}"
                                                                {{ isset($detail->major_code) && $detail->major_code == $val->code ? 'checked' : '' }}>
                                                                @lang($val->name ?? '') - {{ $val->code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số lượng') <small class="text-red">*</small></label>
                                                    <input onchange="getValueTitle()" type="number"
                                                        class="form-control quantity" name="quantity"
                                                        placeholder="@lang('Số lượng')" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Nhóm ngành') <small class="text-red">*</small></label>
                                                    <select onchange="getprofession()" required name="industry_group"
                                                        class="industry_group form-control select2">
                                                        <option value="">Chọn nhóm ngành</option>
                                                        @foreach ($industry_group as $key => $val)
                                                            <option value="{{ $val->code }}"
                                                                {{ isset($detail->industry_group) && $detail->industry_group == $val->code ? 'checked' : '' }}>
                                                                @lang($val->name)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngành nghề') <small class="text-red">*</small></label>
                                                    <input onchange="getValueTitle()" type="text"
                                                        class="form-control profession" name="profession"
                                                        placeholder="@lang('Ngành nghề')" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Bang') <small class="text-red">*</small></label>
                                                    <input onchange="getValueTitle()" type="text"
                                                        class="form-control state" name="state"
                                                        placeholder="@lang('Bang')" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Giới tính') </label>
                                                    <select name="gender_job" class=" form-control select2">
                                                        @foreach ($gender_job as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->gender_job) && $detail->gender_job == $key ? 'checked' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Trạng thái') </label>
                                                    <select name="status" class=" form-control select2">
                                                        @foreach ($job_status as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->status) && $detail->status == $key ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Kì xuất cảnh dự kiến') </label>
                                                    <input onchange="getValueTitle()" type="date"
                                                        class="form-control exit_period" name="exit_period"
                                                        placeholder="@lang('Kì xuất cảnh dự kiến')"
                                                        value="{{ date('Y-m-d', time()) }}">
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Content') <small class="text-red">*</small></label>
                                                        <textarea name="json_params[content]" class="form-control" id="content_vi">{{ $detail->json_params->content ?? old('json_params[content]') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
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
        CKEDITOR.replace('content_vi', ck_options);
        //lấy title
        function getValueTitle() {
            var syllasbuss_code = $(".syllasbuss_code").val();
            var quantity = $(".quantity").val();
            var profession = $(".profession").val();
            var state = $(".state").val();
            var exit_period = $(".exit_period").val();

            var parts = [];

            if (syllasbuss_code) parts.push(syllasbuss_code);
            if (quantity) parts.push(quantity);
            if (profession) parts.push(profession);
            if (state) parts.push(state);
            if (exit_period) parts.push(exit_period);

            var text = parts.join("-");
            $('.job_title').val(text);
        }
        //mã chương trình auto
        function getSyllabusCode() {
            var partner_code = $(".partner_code").val();
            var syllabus_quantity = $(".syllabus_quantity").val();
            var parts = [];
            if (partner_code) parts.push(partner_code);
            if (syllabus_quantity) parts.push(syllabus_quantity);

            var text = parts.join("-");
            $('.syllasbuss_code').val(text);
            getValueTitle()
        }

        function getparnerCode() {
            var select_partner_code = $(".select_partner_code").val();
            if (select_partner_code != '') {
                $(".partner_code").val(select_partner_code);
                getSyllabusCode()
            }
        }

        function getprofession() {
            var industry_group = $.trim($(".industry_group option:selected").text());
            if (industry_group != '') {
                $(".profession").val(industry_group);
                getValueTitle()
            }
        }
    </script>
@endsection
