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
