@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .ml-5 {
            margin-left: 5rem;
        }

        .mt-4 {
            margin-top: 4rem;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
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
                            <h3 class="box-title">@lang('Kết quả: ')
                                {{ $row->student->name ?? '' }}-{{ $row->student->admin_code ?? '' }}

                            ---  Số điểm: {{ $row->score ?? '' }}/100
                            </h3>
                        </div>
                        <div class="box-body">
                            @php
                                $stt_question = 0;
                            @endphp
                            @foreach ($data as $is_type => $option)
                                <p style="width:100%">
                                    <strong>Teil {{ $is_type }}:
                                        {{ $option['content_option'] ?? '' }}</strong>
                                </p>
                                @foreach ($option['topic'] as $topic)
                                    <div class="more_question mt-3 ml-5">
                                        {!! $topic->content !!}
                                    </div>
                                    @if (!empty($topic->audio))
                                        <audio class="audio" src="{{ $topic->audio ?? '' }}" controls
                                            controlslist="nodownload noremoteplayback" type="audio/mp3">
                                        </audio>
                                    @endif
                                    @foreach ($topic->exam_questions as $key => $row)
                                        @php
                                            $stt_question++;
                                        @endphp
                                        @switch($row->is_type)
                                            @case('nhap_dap_an')
                                                <div class="tab-pane active mt-4 ml-5">
                                                    <div class="content_answer" style="margin-bottom: 15px">
                                                        <p>Câu {{ $stt_question }}:
                                                            {!! $row->question !!}
                                                        </p>
                                                    </div>
                                                    <div class=" more_answer">
                                                        <label for="">Đáp án:</label>
                                                        <input type="text" class="form-control"
                                                            name="answer[{{ $val->id }}][{{ $row->id }}]"
                                                            value="{{ $his_answer->{$val->id}->{$row->id} ?? '' }}">
                                                    </div>
                                                </div>
                                            @break

                                            @case('chon_dap_an')
                                                <div class="tab-pane active mt-4 ml-5">
                                                    <div class="content_answer" style="margin-bottom: 15px">
                                                        <p> <strong class="mr-2">{{ $stt_question }}:</strong>
                                                            {!! $row->question !!}
                                                        </p>
                                                    </div>
                                                    @foreach ($row->exam_answers as $k => $answer)
                                                        <div class="d-flex-wap more_answer">
                                                            <div class="col-md-6">
                                                                <div
                                                                    class="form-group input_text {{ in_array($answer->id, $arr_correct_answer) ? 'bg-success' : '' }}">
                                                                    <input type="radio" disabled
                                                                        {{ isset($his_answer->{$topic->id}->{$row->id}) && $his_answer->{$topic->id}->{$row->id} == $answer->answer ? 'checked' : '' }}
                                                                        value="{{ $answer->answer ?? '' }}">
                                                                    <label
                                                                        for="text_answer_{{ $row->id . '_' . $k }}">{{ old('answer') ?? ($answer->answer ?? '') }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @break

                                            @default
                                        @endswitch
                                    @endforeach
                                @endforeach
                            @endforeach
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
