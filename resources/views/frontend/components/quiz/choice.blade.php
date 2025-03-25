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
