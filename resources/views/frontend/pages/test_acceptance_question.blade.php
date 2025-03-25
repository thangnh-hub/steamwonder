@extends('frontend.layouts.test')

@section('content')
    <style>
        .box-title {
            text-transform: uppercase
        }

        #countdown {
            color: red;
            font-size: 20px;
            font-weight: bold
        }

        img {
            max-width: 100%;
            height: auto !important;
        }

        .box-header {
            position: sticky;
            top: 0px;
            background-color: yellow;
            padding: 10px;
            z-index: 999;
        }

        .content_answer {
            font-weight: bold
        }

        .tab-content>.active.answer_eye_training {
            display: flex;
            justify-content: start;
            gap: 30px;
        }

        .answer_eye_training .content_answer p {
            margin: 0;
            padding: 0
        }

        .answer_eye_training .more_answer label {
            margin-bottom: 15px
        }

        .answer_eye_training .input_eye_training {
            width: 60px;
            height: 60px;
            text-align: center;
            border: 1px solid;
        }

        .input_text {
            display: flex;
            gap: 5px;
            align-items: flex-start
        }

        .box-fixed {
            margin-bottom: 0px
        }

        .btn_quiz_choice.active {
            background: #3c8dbc;
            color: #fff;

        }

        .audio {
            margin-bottom: 10px;
            max-width: 100%;
        }


        .ip_listen {
            width: 60px;
            margin: 5px;
            text-align: center;
            padding: 3px;
        }

        @media screen and (max-width: 991px) {
            .box-header .box-title {
                font-size: 14px;
                line-height: 20px;
            }


            .content {
                margin: 0px;
                padding: 0px;
            }


            .tab-content>.active.answer_eye_training {
                justify-content: space-between;
                gap: 20px
            }

            .answer_eye_training .more_answer label {
                font-size: 13px;
                margin-bottom: 0px
            }

            .answer_eye_training .input_eye_training {
                width: 35px;
                height: 35px;
            }

            .nav-tabs-custom>.tab-content {
                padding: 0px;
            }

            .col-lg-12 {
                padding: 0px;
            }
        }
    </style>
    <section class="content" id="box_content">
        <div style="width: 100%;background: #FFF;text-align: center;">
            <img src="{{ asset('/data/dwn.jpg') }}" alt="DWN" style="width: 25%;">
        </div>

        <div class="box">
            <div class="box-header text-center">
                <h3 class="box-title">CHÀO MỪNG {{ $student->name }} - CCCD:{{ $student->json_params->cccd }} ĐẾN VỚI BÀI
                    {{ $exam_session->title }}
                </h3>
                @php
                    $now = new DateTime();
                    $interval = new DateInterval('PT10M');
                    $now->add($interval);
                    $referenceTime = new DateTime($currentDate . ' ' . $currentTime);
                    $interval = $now->diff($referenceTime);
                    $diffInSeconds =
                        $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
                    $time_remaining = $exam_session->time_exam * 60 - $diffInSeconds;
                    $time_countdown_minutes = floor($time_remaining / 60);
                    $time_countdown_seconds = $time_remaining % 60;
                @endphp
                <p class="text-center box-fixed">Bạn có <span id="countdown">{{ $time_countdown_minutes }} :
                        {{ $time_countdown_seconds }}</span> phút làm
                    bài
                </p>
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
                <form role="form" action="{{ route('test_acceptance.student.answer') }}" method="POST"
                    id="form_test_iq">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <!-- form start -->
                                @foreach ($topic->shuffle() as $key => $row_topic)
                                    {{-- lấy câu hỏi --}}
                                    @php
                                        $question = $questions->filter(function ($item, $key) use ($row_topic) {
                                            return $item->id_topic == $row_topic->id;
                                        });
                                    @endphp
                                    <div class="box-body more_question">
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active" style="width: 100%;">
                                                    <p style="width:100%">
                                                        <strong>Đề {{ $loop->index + 1 }}: </strong>
                                                        {{ $row_topic->name ?? '' }}
                                                    </p>
                                                    <div class="more_question">
                                                        {!! $row_topic->content ?? '' !!}
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                @foreach ($question as $key => $row)
                                                    @switch($row_topic->type)
                                                        @case('logic')
                                                            <div class="tab-pane {{ $row->id }} active anser_logic"
                                                                id="tab_{{ $row->id }}">
                                                                <div class="content_answer" style="margin-bottom: 30px">
                                                                    <p>Câu {{ $loop->index + 1 }}:</p>
                                                                    {!! $row->question !!}

                                                                </div>
                                                                <div class=" more_answer">
                                                                    <label for="">Đáp án:</label>
                                                                    <input type="text" class="form-control input_logic"
                                                                        name="answer[{{ $row->id }}]" value="">
                                                                </div>
                                                            </div>
                                                        @break

                                                        @case('math')
                                                            <div class="tab-pane {{ $row->id }} active"
                                                                id="tab_{{ $row->id }}">
                                                                <div class="content_answer">
                                                                    <p>Câu {{ $loop->index + 1 }}:</p>
                                                                    {!! $row->question !!}
                                                                </div>
                                                                <div class=" more_answer">
                                                                    <input type="text" class="form-control"
                                                                        name="answer[{{ $row->id }}]" value=""
                                                                        placeholder="Kết quả">
                                                                </div>
                                                            </div>
                                                        @break

                                                        @case('eye_training')
                                                            <div class="tab-pane {{ $row->id }} active answer_eye_training"
                                                                id="tab_{{ $row->id }}">
                                                                <div class="content_answer" style="margin-bottom: 30px">
                                                                    <p>Câu {{ $loop->index + 1 }}:
                                                                    </p>
                                                                    {!! $row->question !!}
                                                                </div>
                                                                <div class="more_answer">
                                                                    <label>Trả lời:</label>
                                                                    <input type="text" class="form-control input_eye_training"
                                                                        name="answer[{{ $row->id }}]" value=""
                                                                        maxlength="1">
                                                                </div>
                                                            </div>
                                                        @break

                                                        @case('text')
                                                            <div class="content_answer" style="margin-bottom: 30px">
                                                                Câu {{ $loop->index + 1 }}: {!! $row->question !!}
                                                            </div>
                                                            @php
                                                                $shuffledAnswers = (array) $row->json_params->answer;
                                                            @endphp
                                                            @foreach ($shuffledAnswers as $k => $answer)
                                                                <div class="d-flex-wap more_answer">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group input_text">
                                                                            <input type="radio"
                                                                                name="answer[{{ $row->id }}]"
                                                                                id="text_answer_{{ $row->id . '_' . $k }}"
                                                                                value="{{ $k }}">
                                                                            <label
                                                                                for="text_answer_{{ $row->id . '_' . $k }}">{{ old('answer') ?? $answer->value }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @break

                                                        @case('order_table')
                                                            <div class="content_answer" style="margin-bottom: 20px">
                                                                Câu {{ $loop->index + 1 }}: {!! $row->question !!}
                                                            </div>
                                                            @php
                                                                $shuffledAnswers = (array) $row->json_params->answer;
                                                                $arr_answer = [];
                                                                $ques_topic = $questions->filter(function (
                                                                    $item,
                                                                    $key
                                                                ) use ($row_topic) {
                                                                    return $item->id_topic == $row_topic->id;
                                                                });
                                                                if ($ques_topic) {
                                                                    foreach ($ques_topic as $val) {
                                                                        foreach ($val->json_params->answer as $_v) {
                                                                            array_push($arr_answer, $_v);
                                                                        }
                                                                    }
                                                                }

                                                            @endphp
                                                            <div class="d-flex-wap more_answer" style="margin-bottom: 30px"
                                                                data-id="{{ $row->id }}"
                                                                data-count="{{ count($shuffledAnswers) }}">
                                                                @foreach (collect(array_unique($arr_answer))->shuffle() as $val)
                                                                    <button type="button" class="btn_quiz_choice btn"
                                                                        style="margin: 5px"
                                                                        data-answer="{{ trim($val) }}">{{ trim($val) }}</button>
                                                                @endforeach
                                                            </div>
                                                        @break

                                                        @case('connect')
                                                            <div class="content_answer" style="margin-bottom: 20px">
                                                                Câu {{ $loop->index + 1 }}: {!! $row->question !!}
                                                            </div>
                                                            @php
                                                                $answer_left = $row->json_params->answer->left ?? '';
                                                                $answer_right = $row->json_params->answer->right ?? '';
                                                            @endphp
                                                            @foreach ($answer_left as $k => $val)
                                                                <div class="row more_answer" style="margin-bottom: 5px">
                                                                    <div class="col-xs-5 col-md-2">
                                                                        <input type="text" class="form-control"
                                                                            style="pointer-events: none;"
                                                                            name="answer[{{ $row->id }}][left][]"
                                                                            placeholder="Đáp án" value="{{ $val ?? '' }}">
                                                                    </div>
                                                                    <div class="col-xs-2 col-md-1 text-center">
                                                                        <img style="width: 100px; max-width: 80%;"
                                                                            src="{{ url('themes/admin/img/connect.png') }}"
                                                                            alt="">
                                                                    </div>
                                                                    <div class="col-xs-5 col-md-2">
                                                                        <input type="text" class="form-control"
                                                                            name="answer[{{ $row->id }}][right][]"
                                                                            placeholder="Đáp án" value="">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @break

                                                        @case('listen')
                                                            <div class="content_answer" style="margin-bottom: 20px">
                                                                Câu {{ $loop->index + 1 }}: {!! $row->question !!}
                                                            </div>
                                                            <audio class="audio"
                                                                src="{{ $row->json_params->files_audio ?? '' }}" controls
                                                                controlslist="nodownload noremoteplayback" type="audio/mp3">
                                                            </audio>
                                                            <div class="more_answer d-flex-wap">
                                                                @foreach ($row->json_params->answer as $answer)
                                                                    <input type="text" class="form-control ip_listen"
                                                                        name="answer[{{ $row->id }}][]" value=""
                                                                        placeholder="{{ $loop->index + 1 }}">
                                                                @endforeach
                                                            </div>
                                                        @break

                                                        @case('fill_words')
                                                            <div class="content_answer" style="margin-bottom: 10px">
                                                                Câu {{ $loop->index + 1 }}: {!! $row->question !!}
                                                            </div>
                                                            <div class="more_answer d-flex-wap">
                                                                @foreach ($row->json_params->answer as $answer)
                                                                    @foreach (explode(' ', $answer) as $item)
                                                                        <input type="text" class="form-control ip_listen"
                                                                            name="answer[{{ $row->id }}][]" value=""
                                                                            placeholder="{{ $loop->index + 1 }}">
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                        @break

                                                        @default
                                                    @endswitch
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- /.box-body -->
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-info btn_submit">
                                    <i class="fa fa-save"></i> @lang('Nộp bài')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @push('script')
        <script>
            $('.btn_quiz_choice').click(function() {
                var _par = $(this).parents('.more_answer');
                var _count = _par.data('count');
                var _id = _par.data('id');
                var _answer = $(this).data('answer');
                var _active = _par.find('.active').length;

                if (!$(this).hasClass('active')) {
                    if (_active < _count) {
                        $(this).addClass('active');
                        $(this).after('<input class="checked_val" type="hidden" name="answer[' + _id +
                            '][]" value="' +
                            _answer + '">');
                    }
                } else {
                    $(this).removeClass('active');
                    $(this).next().remove();
                }
            })

            function startCountdown(duration, display) {
                let timer = duration,
                    minutes, seconds;
                const interval = setInterval(function() {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    display.textContent = minutes + ":" + seconds;
                    if (--timer < 0) {
                        clearInterval(interval);
                        sessionStorage.setItem('check_acceptance', 'off');
                        $('#form_test_iq').submit();
                    }
                }, 1000);
            }
            window.onload = function() {
                const fiveMinutes = 60 * {{ $time_countdown_minutes }} + {{ $time_countdown_seconds }},
                    display = document.getElementById('countdown');
                startCountdown(fiveMinutes, display);
            };


            $('.btn_submit').click(function(e) {
                e.preventDefault();
                if (!navigator.onLine) {
                    alert("Không thể nộp bài do mất kết nối internet. Vui lòng kiểm lại !");
                    return false;
                }
                sessionStorage.setItem('check_acceptance', 'off');
                $('#form_test_iq').submit();
            })
            var session_check = sessionStorage.getItem('check_acceptance');
            if (session_check == null) {
                sessionStorage.setItem('check_acceptance', 'on');
            }
            setInterval(function() {
                checkStatus();
            }, 10000);

            function checkStatus() {
                var check = sessionStorage.getItem('check_acceptance');
                if (check == 'off') {
                    sessionStorage.removeItem('check_acceptance');
                    location.reload();
                }
                return;
            }
        </script>
    @endpush


@endsection
