
<div class="d-flex justify-content-between align-items-center">
    <div class="voc_title">
        <p class="voc_name">{{ $detail->name }} <span class="voc_prefix">{{ $detail->prefix }}</span></p>
        <p class="voc_transcription">
            <img src="{{ asset('data/cms-image/ic_volume_de.png') }}" alt="icon" class="icon-volume"
                onclick="textToSpeech('{{ $detail->name }}','1.0','voc_audio')">
            <span>{{ $detail->transcription }}</span>
        </p>
        <audio controls id="voc_audio">
            <source src="{{file_exists(public_path('data/vocabulary/' . $detail->name . '-1.0.mp3')) ? url('data/vocabulary/' . $detail->name . '-1.0.mp3'):''}}">
        </audio>
    </div>
    <div class="voc_img">
        @if ($detail->image != '')
            <img src="{{ $detail->image }}" alt="{{ $detail->name }}">
        @endif
    </div>
</div>
<p class="voc_meaning">Nghĩa: {{ $detail->meaning }}</p>
<div class="box_explanation">
    <p class="voc_explanation">Giải thích</p>
    <ul>
        <li>{{ $detail->json_params->explanation->de }}</li>
        <li>{{ $detail->json_params->explanation->vi }}</li>
    </ul>
</div>
<div class="box_sample">
    <p class="voc_sample">Mẫu câu</p>
    {!! nl2br($detail->json_params->sample) !!}
</div>
