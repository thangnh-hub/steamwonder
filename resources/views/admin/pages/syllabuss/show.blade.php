@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .btn-action {
            right: 18px;
            top: 2px;
            position: absolute;
            display: flex !important;
        }
        .flex{
            display: flex;
        }
        .align-items-center{
            align-items: center;
        }
        .nav-tabs-custom{
            box-shadow: unset;
        }
        .c-poiter{
            cursor: pointer;
        }
        .img-width{
            width: 100%;
        }
        .overflow-auto{
            width: 100%;
            overflow-x: auto;
        }
        .overflow-auto::-webkit-scrollbar{
          width: 5px !important;
        }
        .overflow-auto::-webkit-scrollbar-track {
          background: #f1f1f1;border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
          background: rgb(107, 144, 218);border-radius: 10px;
        }
        .table{
            width: 2500px;
            max-width: unset;
        }
        .table td:first-child{
            width: 190px;
        }
        table thead{
            background: rgb(107, 144, 218);
            color: #fff
        }
    </style>
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

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Thông tin chính')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>@lang('Tên chương trình'): </strong></label>
                            <span>{{ $detail->name??"" }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>@lang('Kỹ năng nghe'): </strong></label>
                            <span>Điểm tối thiểu: {{ $detail->json_params->score->listen->min ?? "" }}</span>; 
                            <span>Trọng số: {{ $detail->json_params->score->listen->weight ?? "" }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>@lang('Trình độ'): </strong></label>
                            <span>1</span>
                        </div>
                        <div class="form-group">
                            <label><strong>@lang('Kỹ năng nói'): </strong></label>
                            <span>Điểm tối thiểu: {{ $detail->json_params->score->speak->min ?? "" }}</span>; 
                            <span>Trọng số: {{ $detail->json_params->score->speak->weight ?? "" }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>@lang('Số buổi học'): </strong></label>
                            <span>{{ count($lessonSylabus) ?? $detail->lesson }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>@lang('Kỹ năng đọc'): </strong></label>
                            <span>Điểm tối thiểu: {{ $detail->json_params->score->read->min ?? "" }}</span>; 
                            <span>Trọng số: {{ $detail->json_params->score->read->weight ?? "" }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>@lang('Số buổi học tối thiểu'): </strong></label>
                            <span> 1 </span>
                        </div>
                        <div class="form-group">
                            <label><strong>@lang('Kỹ năng viết'): </strong></label>
                            <span>Điểm tối thiểu: {{ $detail->json_params->score->write->min ?? "" }}</span>; 
                            <span>Trọng số: {{ $detail->json_params->score->write->weight ?? "" }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @isset($lessonSylabus)
            @foreach ($lessonSylabus as $key=> $lesson)
                <hr>
                    <div class="box box-default {{ $key>0?"collapsed-box":"" }}">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $lesson->ordinal }}</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa {{ $key>0?"fa-plus":"fa-minus" }}"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><strong>@lang('Tên buổi học'): </strong></label>
                                        <span>{{ $lesson->title ?? "Chưa cập nhật" }}</span>
                                    </div>
                                    <div class="form-group">
                                        <p><strong>@lang('Nội dung buổi học'): </strong></p>
                                        <code>{!! nl2br($lesson->content ?? "") !!}</code>
                                    </div>
                                    <div class="form-group">
                                        <p><strong>@lang('Mục tiêu buổi học'): </strong></p>
                                        <code>  {!! nl2br($lesson->target ?? "") !!}</code>
                                    </div>
                                    <div class="form-group">
                                        <p><strong>@lang('Nhiệm vụ giảng viên'): </strong></p>
                                        <code>{!! nl2br($lesson->teacher_mission ?? "") !!}</code>
                                    </div>
                                    <div class="form-group">
                                        <p><strong>@lang('Nhiệm vụ của sinh viên'): </strong></p>
                                        <code>{!! nl2br($lesson->student_mission ?? "") !!}</code>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
            @endforeach   
        @endisset         
    </section>
@endsection

@section('script')
    <script>
        
    </script>
@endsection
