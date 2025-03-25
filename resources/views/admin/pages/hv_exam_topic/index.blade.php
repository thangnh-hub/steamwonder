@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trình độ') </label>
                                <select name="id_level" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($levels as $val)
                                        <option value="{{ $val->id ?? '' }}"
                                            {{ isset($params['id_level']) && $params['id_level'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Tổ chức')</label>
                                <select name="organization" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($organization as $val)
                                        <option value="{{ $val }}"
                                            {{ isset($params['organization']) && $params['organization'] == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Hình thức')</small></label>
                                <select name="skill_test" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($skill as $val)
                                        <option value="{{ $val }}"
                                            {{ isset($params['skill_test']) && $params['skill_test'] == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Nhóm phần thi')</small></label>
                                <select name="is_type" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($group as $val)
                                        <option value="{{ $val }}"
                                            {{ isset($params['is_type']) && $params['is_type'] == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
                <div class="pull-right" style="display: flex; margin-left:15px ">
                    <input class="form-control" type="file" name="files" id="fileImport"
                        placeholder="@lang('Select File')">
                    <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                        <i class="fa fa-file-excel-o"></i>
                        @lang('Import dữ liệu')</button>
                </div>

            </div>
            <div class="box-body table-responsive">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('successMessage') !!}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach

                    </div>
                @endif
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Trình độ')</th>
                                <th>@lang('Tổ chức')</th>
                                <th>@lang('Hình thức')</th>
                                <th>@lang('Phần thi')</th>
                                <th>@lang('Nội dung')</th>
                                <th>@lang('Câu hỏi')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>

                                    <td>
                                        {{ $row->level->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ __($row->organization) ?? '' }}
                                    </td>
                                    <td>
                                        {{ __($row->skill_test) ?? '' }}
                                    </td>
                                    <td>
                                        {{ __($row->is_type) ?? '' }}
                                    </td>
                                    <td>
                                        {!! $row->content ?? '' !!}
                                    </td>
                                    <td>
                                        @isset($row->exam_questions)
                                            <ul>
                                                @foreach ($row->exam_questions as $question)
                                                    <li>
                                                        {{ $question->question ?? '' }}
                                                        ({{ __($question->is_type ?? '') }})
                                                        @isset($question->exam_answers)
                                                            <ul>
                                                                @foreach ($question->exam_answers as $answer)
                                                                    <li
                                                                        class="{{ isset($answer->correct_answer) && $answer->correct_answer == true ? 'text-success text-bold' : '' }}">
                                                                        {{ $answer->answer ?? '' }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endisset
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endisset
                                    </td>
                                    <td>
                                        <div class="d-flex-wap ">
                                            <a class="btn btn-sm btn-warning mr-10" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                                method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                    title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('script')
    <script>
        function importFile() {
            show_loading_notification();
            var formData = new FormData();
            var file = $('#fileImport')[0].files[0];
            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('hv_exam_topic.import') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hide_loading_notification();
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    // Get errors
                    hide_loading_notification();
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
@endsection
