@extends('admin.layouts.auth')
@push('style')
    <style>
        .more_answer {
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div style="width: 100%;background: #FFF;text-align: center;">
                <img src="{{ asset('/data/dwn.jpg') }}" alt="DWN" style="width: 25%;">
            </div>
            <section class="content-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>{{ $data_result['massage'] }}</h3>
                                    <p>@lang('Bạn đã trả lời đúng'):
                                        {{ $data_result['count_true_sentence'] }}/{{ $data_result['count_total_sentence'] }}
                                        câu</p>
                                </div>

                                {{-- <a href="{{ $data_result['url'] }}" class="icon">
                                    <i class="fa fa-arrow-circle-right"></i>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="box box-primary">
                            <h3 >Dưới đây là những câu bạn đã trả lời sai. <a href="{{ $data_result['url'] }}" class="small-box-footer">@lang('Nhấn vào đấy')</a> để làm lại bài Test.</a></h3>
                            @foreach ($data_result['arrQuestionFalse'] as $key => $row)
                                <div class="box-body more_question">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active text-danger"
                                                style="display:flex;padding: 10px;  width: 100%;">
                                                <strong>Câu hỏi {{ $key }}: </strong>
                                                {{ $row->question ?? '' }}

                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane {{ $row->id }} active" id="tab_{{ $key }}">
                                                @php
                                                    $shuffledAnswers = (array) $row->json_params->answer;
                                                    usort($shuffledAnswers, function ($a, $b) {
                                                        return strcmp($a->value, $b->value);
                                                    });
                                                @endphp

                                                @foreach ($shuffledAnswers as $k => $answer)
                                                    <div class="d-flex-wap more_answer">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ $answer->value }}
                                                            </div>
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

                    </div>
                </div>


            </section>
        </div>
        {{-- @dd($data_result['arrQuestionFalse']); --}}
    </section>

    </div>
@endsection
@section('script')
@endsection
