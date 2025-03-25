<div class="learn-playlist">
    <div class="title d-flex justify-content-between align-items-center">
        <p>@lang('Nội dung khóa học')</p>
        <div class="d-flex d-md-none btn_bars">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    </div>
    <div class="list_learning position-relative">
        <div class="accordions">
            @isset($list_lesson)
                @foreach ($list_lesson as $items)
                    @php
                        $lesson_user = $items->lesson_user->where('user_id', $user_auth->id)->first();
                    @endphp
                    <div class="accordion_container">
                        <div class="accordion d-flex flex-row align-items-center justify-content-between {{ $items->id == $lesson->id ? 'active' : '' }}">
                            <p title="{{ $items->title }}"> {{ Str::limit($items->title, 40) }}</p>
                        </div>
                        <div class="accordion_panel">
                            <div class="intro-lesson {{ $items->id == $lesson->id && $tab == 'learning' ? 'active' : '' }}">
                                <p> <a
                                        href="{{ $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $items->id, 'learning') }}">@lang('Bài học')
                                </p></a>
                                @if (isset($lesson_user->json_params->tab_active) && in_array('learning', $lesson_user->json_params->tab_active))
                                    <div class="check"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                @endif
                            </div>
                            <div class="intro-lesson {{ $items->id == $lesson->id && $tab == 'ngu_phap' ? 'active' : '' }}">
                                <p> <a
                                        href="{{ $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $items->id, 'ngu_phap') }}">@lang('Ngữ pháp')
                                </p></a>
                                @if (isset($lesson_user->json_params->tab_active) && in_array('ngu_phap', $lesson_user->json_params->tab_active))
                                    <div class="check"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                @endif
                            </div>
                            <div class="intro-lesson {{ $items->id == $lesson->id && $tab == 'tu_vung' ? 'active' : '' }}">
                                <p> <a
                                        href="{{ $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $items->id, 'tu_vung') }}">@lang('Từ vựng')
                                </p></a>
                                @if (isset($lesson_user->json_params->tab_active) && in_array('tu_vung', $lesson_user->json_params->tab_active))
                                    <div class="check"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                @endif
                            </div>

                            <div
                                class="intro-lesson {{ $items->id == $lesson->id && $tab == 'luyen_tap' ? 'active' : '' }}">
                                <p> <a
                                        href="{{ $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $items->id, 'luyen_tap') }}">@lang('Luyện tập')
                                </p></a>
                                @if (isset($lesson_user->percent_point) && $lesson_user->percent_point >= $percent_pass)
                                    <div class="check"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                @endif
                            </div>
                            <div
                                class="intro-lesson {{ $items->id == $lesson->id && $tab == 'tai_lieu' ? 'active' : '' }}">
                                <p> <a
                                        href="{{ $helpers::getRouteLessonDetail($syllabus_title, $syllabus->id, $items->id, 'tai_lieu') }}">@lang('Tài liệu tham khảo')
                                </p></a>
                                @if (isset($lesson_user->json_params->tab_active) && in_array('tai_lieu', $lesson_user->json_params->tab_active))
                                    <div class="check"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            @endisset
        </div>
    </div>
</div>
