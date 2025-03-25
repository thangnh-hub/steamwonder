@extends('admin.layouts.auth')
@push('style')
    <style>
        .more_answer {
            padding: 10px;
        }
    </style>
@endpush
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
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div style="width: 100%;background: #FFF;text-align: center;">
            <img src="{{ asset('/data/dwn.jpg') }}" alt="DWN" style="width: 25%;">
        </div>
        <div class="box">
            <div class="box-header text-center">
                <h3 class="box-title">@lang('WELCOME TO THE PERSONAL COMPETENCIES ASSESSMENT TEST')</h3>
            </div>
            <div class="box-body">
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
                <form role="form" action="{{ route('check_active_staff.submit') }}" method="POST" id="form_question">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <!-- form start -->
                                <input type="hidden" name="email" value="{{ session('login_email') }}">
                                <input type="hidden" name="password" value="{{ session('login_password') }}">
                                @foreach ($rows->shuffle() as $key => $row)
                                    <div class="box-body more_question">
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active" style="display:flex;padding: 10px;  width: 100%;">

                                                    <a href="#tab_{{ $key }}" data-toggle="tab" style="width:100%">
                                                        <strong>Câu hỏi {{ $loop->index + 1 }}: </strong>
                                                        {{ $row->question ?? '' }} ?
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane {{ $row->id }} active"
                                                    id="tab_{{ $key }}">
                                                    @php
                                                        // $shuffledAnswers = (array) $row->json_params->answer;
                                                        // shuffle($shuffledAnswers);
                                                        $shuffledAnswers = (array) $row->json_params->answer;
                                                        usort($shuffledAnswers, function ($a, $b) {
                                                            return strcmp($a->value, $b->value);
                                                        });
                                                    @endphp

                                                    @foreach ($shuffledAnswers as $k => $answer)
                                                        <div class="d-flex-wap more_answer">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ old('answer') ?? $answer->value }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="radio"
                                                                    name="answer[{{ $row->id }}][key]"
                                                                    value="{{ $answer->key }}" required >
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- /.box-body -->

                            </div>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Submit')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    </div>
@endsection
@section('script')
    <script>
        $(function() {
            window.onpageshow = function(event) {
                if (event.persisted) {
                    console.log('is reload page!');
                    document.getElementById("form_question").reset();
                    window.location.reload();
                }
            };
        });
    </script>
@endsection
