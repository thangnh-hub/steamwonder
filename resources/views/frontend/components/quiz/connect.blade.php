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
