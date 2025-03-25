{{-- Check và gọi template tương ứng --}}
@if ($items_quizs != null)
    @switch($items_quizs->type)
        @case('choice')
            @php
                $answer = $items_quizs->json_params->answer ?? [];
                $question_text = $items_quizs->question ?? '';
            @endphp
            <div class="warpers mb-4 mb-md-0">
                <div class="question fw-bold mb-3">
                    <p class="title"> Đọc và chọn đáp
                        án đúng </p>
                    {!! $question_text !!}
                </div>
                <div class="answers_val">
                    <p>Đáp án:</p>
                    <div class="answr_val qustn_snswer_val answers">
                        <div class="list_icon">
                            @foreach ($answer as $key => $val)
                                <button type="button" class="btn btn_quiz_choice btn mr-2 mb-2 mb-lg-0"
                                    data-answer="{{ $val->value }}">{{ $val->value }}</button>
                            @endforeach
                        </div>
                        <input class="checked_val mr-2" type="hidden" name="quiz[]" value="">
                    </div>
                </div>
            </div>
        @break

        @case('order')
            @php
                $answer = $items_quizs->json_params->answer ?? [];
                $origin = $answer;
                do {
                    $question = collect($answer)->shuffle()->toArray();
                } while ($question === $origin);
                $question_text = implode('/', $question);
            @endphp
            <div class="warpers mb-4 mb-md-0">
                <div class="question fw-bold mb-3" data-id="{{ $items_quizs->id }}">
                    <p class="title">Sắp xếp các từ
                        sau thành câu
                        hoàn
                        chỉnh</p>
                    <div class="d-flex mt-4 mb-4 flex-wrap">
                        @foreach ($question as $item)
                            <button type="button" class="btn-light btn_quiz_order btn mr-2 mb-2 mb-lg-0"
                                data-answer="{{ $item }}">{{ $item }}</button>
                        @endforeach
                    </div>
                    <div class="answers_val">
                        <p>Đáp án:</p>
                        <div class="answr_val qustn_snswer_val answers">
                            <div class="box_anser_order d-flex pb-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @break

        @case('fill')
            @php
                $answer = $items_quizs->json_params->answer ?? [];
                $question_text = $items_quizs->question ?? '';
            @endphp

            <div class="warpers mb-4 mb-md-0">
                <div class="question fw-bold mb-3">
                    <p class="title"> Điền từ còn
                        thiếu
                    </p>
                    {!! $question_text !!}
                </div>
                <div class="answers_val">
                    <p>Đáp án:</p>
                    <div class="answr_val qustn_snswer_val answers d-flex flex-wrap ">
                        @for ($i = 1; $i <= count($answer); $i++)
                            <span class="mr-2 mb-2">{{ $i }}.
                                <input type="text" name="quiz[]" value=""></span>
                        @endfor
                    </div>
                </div>
            </div>
        @break

        @case('connect')
            @php
                $answer_left = $items_quizs->json_params->answer->left ?? '';
                $answer_right = $items_quizs->json_params->answer->right ?? '';
                $question_text = $items_quizs->question ?? '';
                $answer_left = collect($answer_left)->shuffle();
                $answer_right = collect($answer_right)->shuffle();
            @endphp

            <div class="warpers mb-4 mb-md-0">
                <div class="question fw-bold mb-3">
                    <p class="title"> Chọn đáp án đúng
                        tương ứng
                    </p>
                    {!! $question_text !!}
                </div>
                <div class="answers_val" data-id = "{{ $items_quizs->id }}">
                    <p>Đáp án:</p>
                    <div class="answr_val qustn_snswer_val answers d-flex flex-wrap justify-content-between">
                        <div class="col-6 col-md-4 box_answer answer_left">
                            @foreach ($answer_left as $item)
                                <button type="button" title="{{ $item }}" class="mb-2 btn btn_answer btn_answer_left"
                                    data-answer="{{ $item }}">
                                    {{ $item }}</button>
                            @endforeach
                        </div>
                        <div class="col-6 col-md-4 box_answer answer_right">
                            @foreach ($answer_right as $item)
                                <button type="button" title="{{ $item }}" class="mb-2 btn btn_answer btn_answer_right"
                                    data-answer="{{ $item }}">{{ $item }}</button>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        @break

        @case('speak')
            @php
                $answer = $items_quizs->json_params->answer ?? [];
                $question_text = $items_quizs->question ?? '';
                $files_audio = $items_quizs->json_params->files_audio ?? '';
            @endphp

            <div class="warpers mb-4 mb-md-0">
                <div class="question fw-bold mb-3">
                    <div class="title voc_transcription d-flex">
                        <div class="icon-volume">
                            <img src="{{ asset('data/cms-image/ic_volume_de.png') }}" alt="icon" class="icon-volume"
                                onclick="textToSpeech('{{ $items_quizs->json_params->answer[0] ?? '' }}','1.0','voc_audio')">
                        </div>
                        <span>{{ $items_quizs->json_params->answer[0] ?? '' }}</span>
                    </div>
                    <audio controls id="voc_audio">
                        <source
                            src="{{ file_exists(public_path('data/vocabulary/' . $items_quizs->json_params->answer[0] ?? '' . '-1.0.mp3')) ? url('data/vocabulary/' . $items_quizs->json_params->answer[0] ?? '' . '-1.0.mp3') : '' }}">
                    </audio>
                </div>
                <div class="answers_val" data-id = "{{ $items_quizs->id }}">
                    <button type="button" class="btn btn-primary" data-id="holdButton">Nhấn
                        và giữ để đọc theo</button>
                    <div class="answr_val qustn_snswer_val answers d-flex flex-wrap mt-3">

                    </div>
                </div>
            </div>
        @break

        @default
            @php
                $answer = $items_quizs->json_params->answer ?? [];
                $question_text = $items_quizs->question ?? '';
            @endphp
            <div class="warpers mb-4 mb-md-0">
                <div class="question fw-bold mb-3">
                    <p class="title"> Đọc và nhập đáp
                        án</p>
                    {!! $question_text !!}


                </div>
                <div class="answers_val">
                    <p>Đáp án:</p>
                    <div class="answr_val qustn_snswer_val answers">
                        <span><input type="text" class="text-left" style="width: 100%" name="quiz[]" value=""></span>
                    </div>
                </div>
            </div>
    @endswitch
@endif
