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
