{{-- Check và gọi template tương ứng --}}
@extends('frontend.layouts.lesson_detail')

@section('content')
    @php
        $lesson_ordinal = $lesson->ordinal ?? '';
        $lesson_title = $lesson->title ?? '';
        $lesson_content = $lesson->content ?? '';
        $lesson_video = $lesson->json_params->file_video ?? '';
        $lesson_grammar = $lesson->json_params->file_grammar ?? '';
        $lesson_document = $lesson->json_params->document ?? '';
        $lesson_target = $lesson->target ?? '';
        $lesson_teacher_mission = $lesson->teacher_mission ?? '';
        $lesson_student_mission = $lesson->student_mission ?? '';
        $lesson_file = $lesson->json_params->file ?? [];

        $courses_title = $courses->name ?? '';
        $syllabus_title = $lesson->syllabus->name ?? '';
        $syllabus_type = $lesson->syllabus->type ?? '';
        $level_title = $courses->level->name ?? '';

        $syllabus_alias = route('frontend.course.detail', Str::slug($syllabus_title) . '-' . $syllabus->id);
        // $grammar = [];
        //  $vocabulary = [];
        // if (isset($lesson->grammars)) {
        //     $grammar = $lesson->grammars->filter(function ($item, $key) {
        //         return $item->type == 'grammar';
        //     });
        // $vocabulary = $lesson->grammars->filter(function ($item, $key) {
        //     return $item->type == 'vocabulary';
        // });
        // }
    @endphp

    <header class="header">
        <div class="header_container">
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="btn-back">
                        <a href="{{ $syllabus_alias }}">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="box_logo d-none d-lg-block">
                        <img src="{{ $setting->logo_header }}" alt="Logo" />
                    </div>
                    <div class="title">{{ $syllabus->name ?? '' }}</div>
                </div>
                <div class="d-flex align-items-center gap-10">
                    <div class="loader position-relative"
                        data-perc="{{ count($user_lesson_active) / count($list_lesson) }}">
                    </div>
                    <p class="d-none d-md-block mr-24">
                        <strong>{{ count($user_lesson_active) }}/{{ count($list_lesson) }}</strong> @lang('bài học')
                    </p>
                </div>
            </div>
        </div>
    </header>
    <div class="learning row {{ $agent->isMobile() == false ? 'full_screen' : '' }}" id="learning">
        <div class="learn_player">
            @switch($tab)
                @case('tu_vung')
                    <div class="learning-center">
                        <div class="tabs">
                            <div class="tabs_container">
                                <div class="tabs overflow-x d-flex flex-row align-items-center justify-content-start ">
                                    <div class="tab active">@lang('Từ vựng')</div>
                                </div>
                                <div class="tab_panels">
                                    <div class="tab_panel active">
                                        <div class="tab_panel_content">
                                            {{-- <div class="row justify-content-between"> --}}
                                            @foreach ($vocabulary as $item_vocabulary)
                                                {{-- <div class="col-6 col-lg-3"> --}}
                                                <div class="box_title box_vocabulary"
                                                    data-name="{{ $item_vocabulary->name ?? '' }}">
                                                    {{ $item_vocabulary->name ?? '' }}
                                                </div>
                                                {{-- </div> --}}
                                            @endforeach
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @break

                @case('ngu_phap')
                    <div class="box_video learning-center">
                        <video class="video" src="{{ $lesson_grammar }}" controls></video>
                    </div>
                    {{-- <div class="learning-center">
                        <div class="tabs">
                            <div class="tabs_container">
                                <div class="tabs overflow-x d-flex flex-row align-items-center justify-content-start ">
                                    <div class="tab active">@lang('Ngữ pháp')</div>
                                </div>
                                <div class="tab_panels">
                                    <div class="tab_panel active">
                                        <div class="tab_panel_content">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <ul>
                                                        @foreach ($grammar as $item_grammar)
                                                            <li>{{ $item_grammar->content }}</li>
                                                        @endforeach
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                @break

                @case('luyen_tap')
                    <div class="learning-center">
                        <div class="tabs">
                            <div class="tabs_container">
                                <div class="tabs overflow-x d-flex flex-row align-items-center justify-content-start ">
                                    <div class="tab active">@lang('Bài tập')</div>
                                </div>
                                <div class="tab_panels">
                                    <div class="tab_panel active">
                                        <div class="tab_panel_content box_quiz" id="quiz">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex justify-content-end">Câu hỏi: <span class="number_quiz"
                                                        data="1">1</span>/ <span
                                                        class="quiz_total">{{ $list_quizs->count() }}</span> </div>
                                                <div class="col-lg-12 d-flex justify-content-center align-items-center flex-wrap">
                                                    <form action="" id="form_quiz" onsubmit="return false">
                                                        @csrf
                                                        @if (isset($list_quizs) && $list_quizs != '' && count($list_quizs) > 0)
                                                            <div class="box-fild-quiz">
                                                                @php
                                                                    $items_quizs = $list_quizs->first();
                                                                @endphp
                                                                {{-- câu hỏi mới --}}
                                                                @if (\View::exists('frontend.components.quiz.'.$items_quizs->type))
                                                                    @include('frontend.components.quiz.'.$items_quizs->type)
                                                                @else
                                                                    {{ 'View: frontend.components.quiz.'.$items_quizs->type.' không tồn tại!' }}
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="box-next-quiz w-100 d-flex justify-content-center align-items-center mt-5">
                                                                <input type="hidden" class="form_lesson_id" name="lesson_id"
                                                                    value="{{ $lesson->id }}">
                                                                <input type="hidden" class="form_quiz_id" name="quiz_id"
                                                                    value="{{ $items_quizs->id }}">
                                                                <input type="hidden" class="form_count_percent"
                                                                    name="count_percent" value="">

                                                                <button class="btn_check_quiz btn btn-primary"
                                                                    data-lesson="{{ $lesson->id }}"
                                                                    data-id="{{ $items_quizs->id }}">@lang('Kiểm tra')</button>
                                                                <button class="btn_next_quiz btn btn-primary"
                                                                    style="display: none" data-lesson="{{ $lesson->id }}"
                                                                    data-id="{{ $items_quizs->id }}">@lang('Tiếp tục')</button>
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @break

                @case('tai_lieu')
                    <div class="learning-center">
                        <div class="tabs">
                            <div class="tabs_container">
                                <div class="tabs overflow-x d-flex flex-row align-items-center justify-content-start ">
                                    <div class="tab active">@lang('Tài liệu tham khảo')</div>
                                </div>
                                <div class="tab_panels">
                                    <div class="tab_panel active">
                                        <div class="tab_panel_content">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="tab_text">
                                                        @foreach ($lesson_file as $val_file)
                                                            <p><a href="{{ $val_file->link }}">{{ $val_file->title }}</a>
                                                            </p>
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
                @break

                @default
                    <div class="box_video learning-center">
                        <video class="video" src="{{ $lesson_video }}" controls></video>
                    </div>
                    <div class="learning-center">
                        <div class="row tabs">
                            @if ($lesson_content != '')
                                <div class="col-lg-12">
                                    <div class="lesson-content">
                                        <h3 class="title">@lang('Nội dung buổi học')</h3>
                                        <div class="content">
                                            {!! nl2br($lesson_content) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($lesson_target != '')
                                <div class="col-lg-12">
                                    <div class="lesson-content">
                                        <h3 class="title">@lang('Mục tiêu buổi học')</h3>
                                        <div class="content">
                                            {!! nl2br($lesson_target) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($lesson_teacher_mission != '')
                                <div class="col-lg-12">
                                    <div class="lesson-content">
                                        <h3 class="title">@lang('Nhiệm vụ giảng viên')</h3>
                                        <div class="content">
                                            {!! nl2br($lesson_teacher_mission) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($lesson_student_mission != '')
                                <div class="col-lg-12">
                                    <div class="lesson-content">
                                        <h3 class="title">@lang('Nhiệm vụ học viên')</h3>
                                        <div class="content">
                                            {!! nl2br($lesson_student_mission) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
            @endswitch

            @include('frontend.components.sidebar.lesson')

        </div>

        <div class="toggle_bottom">
            <div class="box-bars">
                <span class="d-none d-md-block btn-title">{{ $lesson_title }}</span>
                <button class="btn btn_bars" id="btn_bars">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </button>
            </div>
            <a href="{{ $previous_tab != null ? $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $lesson->id, $previous_tab) : ($previous_lesson != null ? $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $previous_lesson->id, $tab_lesson[4]) : 'javascript:void(0)') }}"
                class="btn btn_toggle btn-pre {{ $previous_lesson == null && $previous_tab == null ? 'btn_null' : '' }}">
                <span class="btn-box">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="btn-title">@lang('Bài trước')</span>
                </span>
            </a>
            <a href="{{ $next_tab != null ? $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $lesson->id, $next_tab) : ($next_lesson != null ? $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $next_lesson->id, $tab_lesson[0]) : 'javascript:void(0)') }}"
                class="btn btn_toggle btn-next {{ $next_lesson == null && $next_tab == null ? 'btn_null' : '' }}">
                <span class="btn-box">
                    <span class="btn-title">@lang('Bài tiếp theo')</span>
                    <i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </a>
        </div>

    @endsection
    @push('script')
        <script>
            $(document).ready(function() {
                $('.box_vocabulary').click(function() {
                    var name = $(this).data('name');
                    if (name != '') {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('frontend.get.vocabulary') }}',
                            data: {
                                "keyword": name,
                            },
                            success: function(response) {
                                if (response != null) {
                                    $('#vocabularyModal .content').html(response);
                                    $('#vocabularyModal').modal('show');
                                }
                            },
                            error: function(response) {
                                var errors = response.responseJSON.message;
                                console.log(errors);
                            }
                        });
                    }
                })

                $('.btn_bars').on('click', function() {
                    fullScreen();
                })
                var ctrl = new ScrollMagic.Controller();
                initLoaders();
                initAccordions();
                initTabs();

                function fullScreen() {
                    $('#learning').toggleClass('full_screen');
                    if ($('#learning').hasClass('full_screen')) {
                        $('#btn_bars').html('<i class="fa fa-arrow-right" aria-hidden="true"></i>')
                    } else {
                        $('#btn_bars').html('<i class="fa fa-bars" aria-hidden="true"></i>')
                    }
                }

                function initTabs() {
                    if ($('.tab').length) {
                        $('.tab').on('click', function() {
                            $('.tab').removeClass('active');
                            $(this).addClass('active');
                            var clickedIndex = $('.tab').index(this);
                            var panels = $('.tab_panel');
                            panels.removeClass('active');
                            $(panels[clickedIndex]).addClass('active');
                        });
                    }
                }

                function initAccordions() {
                    if ($('.accordion').length) {
                        var accs = $('.accordion');

                        accs.each(function() {
                            var acc = $(this);

                            if (acc.hasClass('active')) {
                                var panel = $(acc.next());
                                var panelH = panel.prop('scrollHeight') + "px";

                                if (panel.css('max-height') == "0px") {
                                    panel.css('max-height', panel.prop('scrollHeight') + "px");
                                } else {
                                    panel.css('max-height', "0px");
                                }
                            }

                            acc.on('click', function() {
                                if (acc.hasClass('active')) {
                                    acc.removeClass('active');
                                    var panel = $(acc.next());
                                    var panelH = panel.prop('scrollHeight') + "px";

                                    if (panel.css('max-height') == "0px") {
                                        panel.css('max-height', panel.prop('scrollHeight') + "px");
                                    } else {
                                        panel.css('max-height', "0px");
                                    }
                                } else {
                                    acc.addClass('active');
                                    var panel = $(acc.next());
                                    var panelH = panel.prop('scrollHeight') + "px";

                                    if (panel.css('max-height') == "0px") {
                                        panel.css('max-height', panel.prop('scrollHeight') + "px");
                                    } else {
                                        panel.css('max-height', "0px");
                                    }
                                }
                            });
                        });
                    }
                }

                function initLoaders() {
                    if ($(".loader").length) {
                        var loaders = $(".loader");

                        loaders.each(function() {
                            var loader = this;
                            var endValue = $(loader).data("perc");

                            var loaderScene = new ScrollMagic.Scene({
                                    triggerElement: this,
                                    triggerHook: "onEnter",
                                    reverse: false,
                                })
                                .on("start", function() {
                                    var bar = new ProgressBar.Circle(loader, {
                                        color: "#ff8a00",
                                        // This has to be the same size as the maximum width to
                                        // prevent clipping
                                        strokeWidth: 2,
                                        trailWidth: 1,
                                        trailColor: "transparent",
                                        easing: "easeInOut",
                                        duration: 1400,
                                        text: {
                                            autoStyleContainer: false,
                                        },
                                        from: {
                                            color: "#ff8a00",
                                            width: 2
                                        },
                                        to: {
                                            color: "#ff8a00",
                                            width: 2
                                        },
                                        // Set default step function for all animate calls
                                        step: function(state, circle) {
                                            circle.path.setAttribute(
                                                "stroke",
                                                state.color
                                            );
                                            circle.path.setAttribute(
                                                "stroke-width",
                                                state.width
                                            );

                                            var value = Math.round(
                                                circle.value() * 100
                                            );
                                            if (value === 0) {
                                                circle.setText("0%");
                                            } else {
                                                circle.setText(value + "%");
                                            }
                                        },
                                    });
                                    bar.text.style.fontSize = "12px";
                                    bar.text.style.fontWeight = "700";
                                    bar.text.style.lineheight = "30px";
                                    bar.text.style.color = "#fff";

                                    bar.animate(endValue); // Number from 0.0 to 1.0
                                })
                                .addTo(ctrl);
                        });
                    }
                }
            });

            function textToSpeech(text, speak = 1.0, id) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('frontend.text_to_speech.post') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        text: text,
                        speakingRate: speak,
                    },
                    success: function(response) {
                        if (response != '') {
                            var audioElement = $('#' + id).find('source');
                            audioElement.attr('src', response);
                            var audioPlayer = $('#' + id)[0];
                            audioPlayer.load();
                            audioPlayer.play();
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
                        alert(elementErrors);
                    }
                });
            }
        </script>

        <script>
            (function($) {
                initializeHoldButtons()
                let total = 0;
                $(document).on('click', '.btn_check_quiz', function(e) {
                    var _this = $(this);
                    var lesson_id = $(this).data('lesson');
                    var quiz_id = $(this).attr('data-id');
                    var quiz_number = Number($('.number_quiz').attr('data'));
                    var quiz_total = Number($('.quiz_total').html());
                    var answer = [];

                    var box_html = $('.box-fild-quiz');
                    var box_ansers = $('.answers_val');

                    $('input[name="quiz[]"]').each(function() {
                        answer.push($(this).val());
                    });
                    var formData = $('#form_quiz').serialize();

                    $.ajax({
                        type: "POST",
                        url: '{{ route('frontend.check.quiz') }}',
                        data: formData,
                        // data: {
                        //     "_token": "{{ csrf_token() }}",
                        //     "lesson_id": lesson_id,
                        //     "quiz_id": quiz_id,
                        //     "answer": answer,
                        //     "count_percent_point": total,
                        // },
                        success: function(response) {
                            if (response != null) {
                                _this.hide();
                                $('.btn_next_quiz').show();
                                $('.form_quiz_id').val(response.data.next_quiz_id);
                                var _html = "<p class='fw-bold'>Rất tiếc, câu trả lời chưa đúng!</p>";
                                if (response.data.result == true) {
                                    total++;
                                    $('.form_count_percent').val(total);
                                    var _html = "<p class='fw-bold'>Hoàn toàn chính xác</p>";
                                }
                                if (quiz_number == quiz_total) {
                                    $('.btn_next_quiz').html('Kết thúc')
                                }
                                box_ansers.append(_html);
                            }

                        },
                        error: function(response) {
                            var errors = response.responseJSON.message;
                            console.log(errors);
                        }
                    });
                })

                $(document).on('click', '.btn_next_quiz', function(e) {
                    var quiz_id = $('this').attr('data-id');
                    quiz_id = $('.form_quiz_id').val();
                    var quiz_number = Number($('.number_quiz').attr('data'));
                    var quiz_total = Number($('.quiz_total').html());
                    $(this).hide();
                    if (quiz_id == '') {
                        var lesson_id = $('.btn_check_quiz').data('lesson');
                        var _html = "<p class='text-result'>Bạn đã hoàn thành bài kiểm tra với số câu đúng là: " +
                            total + "</p>";
                        _html +=
                            `<div class="d-flex justify-content-center mt-4"><button onclick="location.reload()" class="btn btn-primary">@lang('Làm lại')</button></div>`;
                        $('.box-fild-quiz').after(_html).hide();
                        updatePointUserLesson(total, lesson_id);

                    } else {
                        $('.btn_check_quiz').show();
                        getViewQuiz(quiz_id, 'box-fild-quiz', 'number_quiz', quiz_total);

                    }

                })
                $(document).on('click', '.btn_answer', function(e) {
                    var id_quiz = $(this).parents('.answers_val').data('id');
                    var box_answers_val = $(this).parents('.answers_val');
                    // check xem đã deactive chưa
                    if (!$(this).hasClass('deactive') && !$(this).parents('.box_answer').hasClass('active')) {
                        // check xem left hay right
                        if ($(this).hasClass('btn_answer_left')) {
                            $(this).parents('.answer_left').find('.btn_answer_left').removeClass('active');
                            $(this).addClass('active');
                        } else {
                            $(this).parents('.answer_right').find('.btn_answer_right').removeClass('active');
                            $(this).addClass('active');
                        }
                        $(this).parents('.box_answer').addClass('active');
                        // kiểm tra 2 bên đều đã chọn thì gọi ajax check đáp án
                        var _box_parents = $(this).parents('.answr_val');

                        if (_box_parents.find('.answer_left').hasClass('active') && _box_parents.find(
                                '.answer_right').hasClass('active')) {
                            var answer_left = _box_parents.find('.answer_left');
                            var answer_right = _box_parents.find('.answer_right');
                            var data_left = answer_left.find('.active').attr('data-answer');
                            var data_right = answer_right.find('.active').attr('data-answer');
                            console.log(data_left);
                            console.log(data_right);
                            $.ajax({
                                type: "POST",
                                url: "{{ route('check_answer_quiz') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    id_quiz: id_quiz,
                                    data_left: data_left,
                                    data_right: data_right
                                },
                                success: function(response) {
                                    if (response == true) {
                                        var _html = `
                                <input type="hidden" name="quiz[left][]" value="` + data_left + `">
                                <input type="hidden" name="quiz[right][]" value="` + data_right +
                                            `">`;
                                        box_answers_val.append(_html);
                                        setTimeout(function() {
                                            answer_left.find('.active').addClass('deactive');
                                            answer_right.find('.active').addClass('deactive');
                                            $('.box_answer, .btn_answer').removeClass('active');
                                        }, 1000);
                                    } else {
                                        setTimeout(function() {
                                            answer_left.find('.active').addClass('error');
                                            answer_right.find('.active').addClass('error');
                                        }, 500);
                                        setTimeout(function() {
                                            answer_left.find('.active').removeClass('error')
                                                .removeClass('active');
                                            answer_right.find('.active').removeClass('error')
                                                .removeClass(
                                                    'active');;
                                            $('.box_answer, .btn_answer').removeClass('active');
                                        }, 1000);

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
                                    alert(elementErrors);
                                }

                            });
                        }
                    }
                })

                $(document).on('click', '.btn_quiz_order', function(e) {
                    if (!$(this).hasClass('deactive')) {
                        var id = $(this).parents('.question').attr('data-id')
                        var anser = $(this).attr('data-answer');
                        var html_anser = `<button type="button" class="btn btn_anser_order mr-2 mb-2 mb-lg-0">` +
                            anser + `</button>
                    <input type="hidden" name="quiz[]" value="` + anser + `">`;
                        $(this).addClass('deactive')
                        $(this).parents('.question').find('.box_anser_order').append(html_anser);
                    }

                })
                $(document).on('click', '.btn_quiz_choice', function(e) {
                    var answer = $(this).html();
                    $(this).parents('.answr_val').find('.btn_quiz_choice').removeClass('active');
                    $(this).addClass('active');
                    $(this).parents('.answr_val').find('.checked_val').val(answer);

                })
                $(document).on('click', '.btn_anser_order', function(e) {
                    var answer = $(this).html();
                    $(this).parents('.question').find('[data-answer="' + answer + '"]').removeClass('deactive');
                    $(e.target).next('input').remove()
                    $(this).remove();
                })

                let chunks = [];
                let recorder;
                async function startRecording() {
                    console.log('Holding...');
                    var _parents = $(this).parents('.answers_val');
                    _parents.find('.answr_val').html('Holding...');
                    var id = _parents.data("id");
                    var _html = '';
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    recorder = new MediaRecorder(stream);
                    recorder.ondataavailable = function(e) {
                        chunks.push(e.data);
                    };

                    recorder.onstop = async function() {
                        const blob = new Blob(chunks, {
                            type: 'audio/wav'
                        });
                        chunks = [];
                        const audioURL = URL.createObjectURL(blob);
                        // Gửi tệp âm thanh lên server
                        const formData = new FormData();
                        formData.append('audio', blob);
                        formData.append('_token', '{{ csrf_token() }}');
                        var url = "{{ route('frontend.transcribe.upload') }}"
                        try {
                            const response = await fetch(url, {
                                method: 'POST',
                                body: formData,
                            });
                            if (!response.ok) {
                                throw new Error('Lỗi kết nối');
                            }
                            const _response = await response.json();
                            _response.data.forEach(item => {
                                if (item != '') {
                                    _html += `<button type="button" class="btn_quiz_speak btn mr-2 mb-2 mb-lg-0 active">
                                ` + item + `</button><input type="hidden" name="quiz[]" value="` + item +
                                        `">`;
                                }
                            });
                            _parents.find('.answr_val').html(_html);
                        } catch (error) {
                            console.error('Fetch error:', error);
                            alert(error);
                        }
                    };

                    recorder.start();
                }

                function stopRecording() {
                    console.log('Not Holding');
                    var _parents = $(this).parents('.answers_val');
                    _parents.find('.answr_val').html('Not Holding');
                    recorder.stop();
                }

                function initializeHoldButtons() {
                    let elements = document.querySelectorAll('[data-id="holdButton"]');

                    elements.forEach(function(element) {
                        // Sự kiện chuột
                        element.addEventListener('mousedown', startRecording);
                        element.addEventListener('mouseup', stopRecording);
                        element.addEventListener('touchstart', startRecording);
                        element.addEventListener('touchend', stopRecording);
                        element.addEventListener('touchcancel', stopRecording);
                        element.addEventListener('touchleave', stopRecording);
                    });
                }

                // let elements = document.querySelectorAll('[data-id="holdButton"]');
                // elements.forEach(function(element) {
                //     // Sự kiện chuột
                //     element.addEventListener('mousedown', startRecording);
                //     element.addEventListener('mouseup', stopRecording);
                //     element.addEventListener('touchstart', startRecording);
                //     element.addEventListener('touchend', stopRecording);
                //     element.addEventListener('touchcancel', stopRecording);
                //     element.addEventListener('touchleave', stopRecording);
                // });

                function getViewQuiz(id, cl, cl_number, q_total = 1) {
                    var quiz_number = Number($('.' + cl_number).attr('data'));
                    var quiz_total = q_total;
                    var box = $('.' + cl);
                    var box_number = $('.' + cl_number);
                    $.ajax({
                        type: "GET",
                        url: '{{ route('frontend.get.viewnextquiz') }}',
                        data: {
                            "id": id,
                        },
                        success: function(response) {
                            $('.' + cl_number).attr('data', quiz_number + 1).html(quiz_number + 1);
                            box.slideUp(300, function() {
                                box.html(response).slideDown(300, function() {
                                    initializeHoldButtons();
                                });
                            });
                        },
                        error: function(response) {
                            var errors = response.responseJSON.message;
                            console.log(errors);
                        }
                    });
                }

                function updatePointUserLesson(point, id) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('frontend.update.point') }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "point": point,
                        },
                        success: function(response) {
                            console.log(response);

                        },
                        error: function(response) {
                            var errors = response.responseJSON.message;
                            console.log(errors);
                        }
                    });
                }

            })(jQuery);
        </script>
    @endpush
