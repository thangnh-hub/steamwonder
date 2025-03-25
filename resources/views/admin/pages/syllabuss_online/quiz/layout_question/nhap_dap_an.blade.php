<div class="tab-content order">
    <div class="tab-pane active ">
        <strong>Đáp án:</strong>
        <div class="d-flex-wap list_answer">
            @if (isset($quiz->json_params->answer) && $quiz->json_params->answer != null)
                @foreach ($quiz->json_params->answer as $k => $answer)
                    <div class="col-md-3 more_answer">
                        <div class="form-group input-group">
                            <input type="text" class="form-control" name="json_params[answer][]" placeholder="Đáp án"
                                value="{{ $answer ?? '' }}">
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-3 more_answer">
                    <div class="form-group input-group">
                        <input type="text" class="form-control" name="json_params[answer][]" placeholder="Đáp án"
                            value="">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
