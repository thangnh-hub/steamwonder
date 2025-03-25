<div class="row lesson-item">
    <div class="col-lg-12">
        <div class="box box-primary">
            <input type="hidden" value="Buổi học thứ 1" name="ordinal">
            <div class="nav-tabs-custom">
                <div class="tab_offline">
                    <div class="tab-pane active">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tên buổi học</label>
                                <input class="form-control" type="text" name="title"
                                    placeholder="Tên buổi học"value="{{ $lesson->title ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nội dung buổi học</label>
                                <textarea class="form-control" name="content" cols="30" rows="4" placeholder="Nội dung buổi học">{{ $lesson->content ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mục tiêu buổi học</label>
                                <textarea class="form-control" name="target" cols="30" rows="4" placeholder="Mục tiêu buổi học">{{ $lesson->target ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nhiệm vụ giảng viên</label>
                                <textarea class="form-control" name="teacher_mission" cols="30" rows="4" placeholder="Nhiệm vụ giảng viên">{{ $lesson->teacher_mission ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nhiệm vụ học viên</label>
                                <textarea class="form-control" name="student_mission" cols="30" rows="4" placeholder="Nhiệm vụ học viên">{{ $lesson->student_mission ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>File bài học</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="files" class="btn btn-primary file" data-type="product">
                                            <i class="fa fa-picture-o"></i> @lang('choose')
                                        </a>
                                    </span>
                                    <input id="files" class="form-control" type="text"
                                        name="json_params[file_video]"
                                        value="{{ $lesson->json_params->file_video ?? '' }}"
                                        placeholder="@lang('File')...">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>File ngữ pháp</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="files_grammar" class="btn btn-primary file" data-type="product">
                                            <i class="fa fa-picture-o"></i> @lang('choose')
                                        </a>
                                    </span>
                                    <input id="files_grammar" class="form-control" type="text" name="json_params[file_grammar]"
                                    value="{{ $lesson->json_params->file_grammar ?? '' }}"
                                        placeholder="@lang('File Grammar')...">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Tài liệu</label>
                        </div>

                        @isset($lesson->json_params->file)
                            @foreach ($lesson->json_params->file as $key_file => $file_less)
                                <div class="col-md-12 file-item">
                                    <div class="form-group">
                                        <div class="col-md-5 pl-0">
                                            <input value="{{ $file_less->title ?? '' }}"
                                                name="json_params[file][{{ $key_file }}][title]"
                                                class="form-control mb-15" type="text" placeholder="Tiêu đề...">
                                        </div>
                                        <div class="col-md-5">
                                            <input value="{{ $file_less->link ?? '' }}"
                                                name="json_params[file][{{ $key_file }}][link]" class="form-control"
                                                type="text" placeholder="Link...">
                                        </div>
                                        @if ($key_file)
                                            <div class="col-md-2">
                                                <button type="button" onclick="delete_file(this)"
                                                    class="btn btn-sm btn-danger">Xóa</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endisset
                        <div class="box-file">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" data-num="0" onclick="add_file(this)"
                                    class="btn btn-warning btn-sm">
                                    Thêm tài liệu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<script>
    $('.file').filemanager('Files', {
        prefix: '{{ route('ckfinder_browser') }}'
    });
</script>
{{-- @include('admin.pages.syllabuss_online.layout_update.grammar') --}}

@include('admin.pages.syllabuss_online.layout_update.vocabulary')
