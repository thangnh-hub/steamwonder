@extends('admin.layouts.app')
@push('style')
    <style>
        .more_answer {
            padding: 10px;
        }
    </style>
@endpush
@section('title')
    @lang($module_name)
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List question')</h3>
            </div>
            <div class="box-body">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('errorMessage') }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('successMessage') }}
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
                <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_question">
                    @csrf
                    @if (Request::get('lang') != '' && Request::get('lang') != $item->lang_locale)
                        <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <!-- form start -->
                                @foreach ($rows as $key => $row)
                                    <div class="box-body more_question">
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active" style="display:flex;padding: 10px; width: 100%;">
                                                    <strong>Câu hỏi {{ $loop->index + 1 }}:</strong>
                                                    <a href="#tab_{{ $key }}" data-toggle="tab"
                                                        style=" width: 90%;">
                                                        <input type="text" class="form-control"
                                                            name="list[{{ $row->id }}][question]"
                                                            placeholder="@lang('Question')"
                                                            value="{{ $row->question ?? '' }}" required>
                                                    </a>
                                                    <button onclick="_delete_question(this)" type="button"
                                                        class="btn btn-sm btn-danger"
                                                        data-question="{{ $row->id }}">Xóa</button>
                                                    <input type="hidden" name="list[{{ $row->id }}][id]"
                                                        value="{{ $row->id }}">
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane {{ $row->id }} active"
                                                    id="tab_{{ $key }}">
                                                    <strong>Đáp án:</strong>
                                                    @isset($row->json_params->answer)
                                                        @foreach ($row->json_params->answer as $k => $answer)
                                                            <div class="d-flex-wap more_answer">

                                                                {{-- <div class="col-md-1">
                                                            <label>{{ $k }}: </label>
                                                        </div> --}}
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"
                                                                            name="list[{{ $row->id }}][json_params][answer][{{ $k }}][value]"
                                                                            placeholder="@lang('Answer')"
                                                                            value="{{ old('answer') ?? ($answer->value ?? '') }}"
                                                                            required>
                                                                        <input type="hidden"
                                                                            name="list[{{ $row->id }}][json_params][answer][{{ $k }}][key]"
                                                                            value="{{ $k }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <input type="checkbox"
                                                                        name="list[{{ $row->id }}][json_params][answer][{{ $k }}][boolean]"
                                                                        value="{{ $answer->boolean ?? 0 }}"
                                                                        {{ isset($answer->boolean) && $answer->boolean == 1 ? 'checked' : '' }}
                                                                        onchange="updateCheckboxValue(this)">
                                                                    {{-- <select name="list[{{$row->id}}][json_params][answer][{{ $k }}][boolean]" class="form-control" style="width: 100%;">
                                                                <option value="">@lang('Please select')</option>
                                                                @foreach ($booleans as $b => $val)
                                                                    <option value="{{ $b }}"
                                                                        {{ isset($answer->boolean) && $b == $answer->boolean ? 'selected' : '' }}>
                                                                        @lang($booleans[$b])</option>
                                                                @endforeach
                                                            </select> --}}
                                                                </div>
                                                                <button onclick="_delete_answer(this)" type="button"
                                                                    class="btn btn-sm btn-danger"
                                                                    data-answer="{{ $k }}"
                                                                    data-question="{{ $row->id ?? 0 }}">Xóa</button>
                                                            </div>
                                                        @endforeach
                                                    @endisset

                                                </div>
                                                <button data-answer="{{ $k }}"
                                                    data-question="{{ $row->id ?? 0 }}"
                                                    class="form-group btn btn-primary mb-2 add_answer {{ $row->id ?? 0 }}"
                                                    type="button"><i class="fa fa-plus"></i> @lang('Add answer')</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- /.box-body -->

                            </div>
                            <button data-question="{{ $row->id ?? ($key ?? 0) }}"
                                class="form-group btn btn-primary mb-2 add_question" type="button"><i
                                    class="fa fa-plus"></i> @lang('Add question')</button>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    </div>
@endsection
@section('script')
    <script>
        function updateCheckboxValue(checkbox) {
            if (checkbox.checked) {
                checkbox.value = 1;
            } else {
                checkbox.value = 0;
            }
        }

        function _delete_question(th) {
            var _count = Number($(th).attr('data-question'));
            $('.add_question').attr('data-question', _count - 1);
            var newQuestionHTML = `<input type="hidden" name="item_delete[]" value="` + (_count) + `">`;
            $(th).parents('.more_question').html(newQuestionHTML);
        }
        $(document).on('click', '.add_question', function() {
            var _count = Number($(this).attr('data-question'));
            var _count_an = 1;
            var _html = `<div class="box-body more_question">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active" style="display:flex;padding: 10px; width: 100%">
                                    <strong>Câu hỏi :</strong>
                                    <a href="#tab_` + (_count + 1) + `" data-toggle="tab" style="width: 90%;">

                                        <input type="text" class="form-control" name="list[` + (_count + 1) +
                `][question]"
                                            placeholder="@lang('Question')"
                                            required>
                                    </a>
                                    <button onclick="_delete_question(this)" type="button" class="btn btn-sm btn-danger" data-question="` +
                (_count + 1) + `">Xóa</button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane ` + (_count + 1) + ` active" id="tab_` + (_count + 1) + `">
                                    <strong>Đáp án :</strong>
                                    <div class="d-flex-wap">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="list[` + (_count + 1) +
                `][json_params][answer][` + (_count_an) + `][value]"
                                                    placeholder="@lang('Answer')" value="{{ old('answer') }}"
                                                    required>
                                                <input type="hidden" name="list[` + (_count + 1) +
                `][json_params][answer][` + (_count_an) + `][key]" value="` + (_count_an) + `">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <input type="checkbox" name="list[` + (_count + 1) +
                `][json_params][answer][` + (_count_an) + `][boolean]" onchange="updateCheckboxValue(this)">
                                        </div>
                                    </div>

                                </div>
                                <button data-answer="` + (_count_an) + `" data-question="` + (_count + 1) +
                `" class="form-group btn btn-primary mb-2 add_answer ` + (_count + 1) + `" type="button"><i class="fa fa-plus"></i> @lang('Add answer')</button>
                            </div>
                        </div>
                    </div>`;
            $('.box.box-primary').append(_html);
            $('.add_question').attr('data-question', _count + 1);
        })
    </script>
    <script>
        function _delete_answer(th) {
            var _count = Number($(th).attr('data-question'));
            var _count_an = Number($(th).attr('data-answer'));
            $('.add_answer.' + _count).attr('data-answer', _count_an - 1);
            $(th).parents('.more_answer').remove();
        }
        $(document).on('click', '.add_answer', function() {
            var _count = Number($(this).attr('data-question'));
            var _count_an = Number($(this).attr('data-answer'));
            var _html = `<div class="d-flex-wap more_answer">

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="list[` + (_count) +
                `][json_params][answer][` + (_count_an + 1) + `][value]"
                                    placeholder="@lang('Answer')" value="{{ old('answer') }}"
                                    required>
                                <input type="hidden" name="list[` + (_count) + `][json_params][answer][` + (_count_an +
                    1) + `][key]" value="` + (_count_an + 1) + `">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" name="list[` + (_count) + `][json_params][answer][` + (_count_an +
                    1) +
                `][boolean]" onchange="updateCheckboxValue(this)">

                        </div>
                        <button onclick="_delete_answer(this)" type="button" class="btn btn-sm btn-danger" data-answer="` +
                (
                    _count_an + 1) + `" data-question="` + (_count) + `">Xóa</button>
                    </div>`;

            $('.tab-pane.' + _count).append(_html);
            $(this).attr('data-answer', _count_an + 1);
        })
    </script>

    <script>
        $(document).ready(function() {
            // Routes get all
            var routes = @json(App\Consts::ROUTE_NAME ?? []);
            $(document).on('change', '#route_name', function() {
                let _value = $(this).val();
                let _targetHTML = $('#template');
                let _list = filterArray(routes, 'name', _value);
                let _optionList = '<option value="">@lang('Please select')</option>';
                if (_list) {
                    _list.forEach(element => {
                        element.template.forEach(item => {
                            _optionList += '<option value="' + item.name + '"> ' + item
                                .title + ' </option>';
                        });
                    });
                    _targetHTML.html(_optionList);
                }
                $(".select2").select2();
            });

        });
    </script>
@endsection
