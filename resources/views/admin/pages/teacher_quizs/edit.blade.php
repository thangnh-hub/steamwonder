@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
            </a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
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

        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST"
            id="form_product">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Chỉnh sửa câu hỏi')</h3>
                        </div>
                        <div class="box-body more_question">
                            <div class="nav-tabs-custom">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="form-group">
                                            <label>@lang('Question') <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="question"
                                                placeholder="@lang('Question')"
                                                value="{{ $detail->question ?? old('question') }}" required>
                                        </div>
                                        <label>Đáp án:</label>
                                        @isset($detail->json_params->answer)
                                            @foreach ($detail->json_params->answer as $key => $item)
                                                <div class="row more_answer">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                name="json_params[answer][{{ $key }}][value]"
                                                                placeholder="@lang('Answer')" value="{{ $item->value }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input style="margin-right:25px;" type="checkbox" class="check_answer"
                                                            name="json_params[answer][{{ $key }}][boolean]"
                                                            value="{{ isset($item->boolean) && $item->boolean == 1 ? 1 : 0 }}"
                                                            {{ isset($item->boolean) && $item->boolean == 1 ? 'checked' : '' }}
                                                            onchange="updateCheckboxValue(this)">

                                                        <button onclick="_delete_answer(this)" type="button"
                                                            class="btn btn-sm btn-danger">Xóa</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endisset

                                    </div>
                                    <button class="form-group btn btn-primary mb-2 add_answer" type="button"><i
                                            class="fa fa-plus"></i> @lang('Add answer')</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

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

        function _delete_answer(th) {
            $(th).parents('.more_answer').remove();
        }
        $(document).on('click', '.add_answer', function() {
            const timestampInSeconds = Date.now();
            var _html = `<div class="row more_answer">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="json_params[answer][` +
                timestampInSeconds +
                `][value]" placeholder="@lang('Answer')" value="" required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <input style="margin-right:25px;" type="checkbox" class="check_answer" name="json_params[answer][` +
                timestampInSeconds + `][boolean]" value="0" onchange="updateCheckboxValue(this)">
                           
                            <button onclick="_delete_answer(this)" type="button" class="btn btn-sm btn-danger">Xóa</button>
                        </div>
                    </div>`;

            $('.tab-pane').append(_html);
        })
    </script>
@endsection
