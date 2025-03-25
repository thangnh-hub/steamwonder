@extends('admin.layouts.auth')

@section('title')
    {{ $module_name }}
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }} (Đúng {{ $row->total_true }}/{{ $row->total_question }} câu hỏi)
        </h1>
        <h3>Bạn bị sai ở những câu hỏi sau đây:</h3>
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
            @foreach ($arrQuestionFalse as $row)
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <!-- form start -->
                        <div class="">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active" style="display:flex;padding: 10px;  width: 100%;">
                                        <h4><strong>Câu hỏi: </strong>
                                            {{ $row->question ?? '' }}</h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            @endforeach
             
            <div class="col-lg-12">
                <a href="{{ route('test_teacher.test') }}">
                    <button type="button" class="btn btn-info">
                        @lang('Làm lại')
                    </button>
                </a>
                <a href="{{ route('admin.login') }}">
                    <button type="button" class="btn btn-danger pull-right">
                        @lang('Đóng')
                    </button>
                </a>
            </div>
            
        </div>
@endsection