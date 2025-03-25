@extends('admin.layouts.app')

@section('title')
  @lang($module_name)
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      @lang($module_name)
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i> @lang('Add')</a>
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

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('Update form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5>
                    @lang('General information')
                    <span class="text-danger">*</span>
                  </h5>
                </a>
              </li>
              </li>

              <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </ul>

            <div class="tab-content">

              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Parent element')</label>
                      <select name="parent_id" id="parent_id" class="form-control select2" style="width: 100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}" {{ $detail->parent_id == $item->id ? 'selected' : '' }}>
                              {{ $item->title }}</option>

                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ $detail->parent_id == $sub->id ? 'selected' : '' }}>- -
                                  {{ $sub->title }}</option>

                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ $detail->parent_id == $sub_child->id ? 'selected' : '' }}>
                                      - - - - {{ $sub_child->title }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        @foreach (App\Consts::STATUS as $key => $value)
                          <label>
                            <input type="radio" name="status" value="{{ $value }}"
                              {{ $detail->status == $value ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        @lang('Title')
                        <small class="text-red">*</small>
                      </label>
                      <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                        value="{{ old('title') ?? $detail->title }}" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        @lang('Block type')
                        <small class="text-red">*</small>
                      </label>
                      <select name="block_code" id="block_code" class="form-control select2" style="width: 100%" required>
                        <option value="">@lang('Please select')</option>
                        @foreach ($blocks as $item)
                          <option value="{{ $item->block_code }}"
                            {{ $item->block_code == $detail->block_code ? 'selected' : '' }}>{{ $item->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Layout')</label>
                      <select name="json_params[layout]" id="block_layout" class="form-control select2"
                        style="width: 100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($blocks as $item)
                          @if ($item->block_code == $detail->block_code)
                            @php
                              $json_params = json_decode($item->json_params);
                            @endphp
                            @isset($json_params->layout)
                              @foreach ($json_params->layout as $name => $value)
                                <option value="{{ $value }}"
                                  {{ isset($detail->json_params->layout) && $value == $detail->json_params->layout ? 'selected' : '' }}>
                                  {{ __($value) }}
                                </option>
                              @endforeach
                            @endisset
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Style')</label>
                      <select name="json_params[style]" id="block_style" class="form-control select2" style="width: 100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($blocks as $item)
                          @if ($item->block_code == $detail->block_code)
                            @php
                              $json_params = json_decode($item->json_params);
                            @endphp
                            @isset($json_params->style)
                              @foreach ($json_params->style as $name => $value)
                                <option value="{{ $value }}"
                                  {{ isset($detail->json_params->style) && $value == $detail->json_params->style ? 'selected' : '' }}>
                                  {{ __($value) }}
                                </option>
                              @endforeach
                            @endisset
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Brief')</label>
                      <textarea name="brief" id="brief" class="form-control" rows="5">{{ old('brief') ?? $detail->brief }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Content')</label>
                      <textarea name="content" id="content" class="form-control"
                        rows="5">{{ old('content') ?? $detail->content }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Url redirect')</label>
                      <input type="text" class="form-control" name="url_link" placeholder="@lang('Url redirect')"
                        value="{{ old('url_link') ?? $detail->url_link }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Url redirect title')</label>
                      <input type="text" class="form-control" name="url_link_title"
                        placeholder="@lang('Url redirect title')"
                        value="{{ old('url_link_title') ?? $detail->url_link_title }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ old('iorder') ?? $detail->iorder }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Icon')</label>
                      <input type="text" class="form-control" name="icon" placeholder="Ex: fa fa-folder"
                        value="{{ old('icon') ?? $detail->icon }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('Select')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('Image source')" value="{{ $detail->image }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image != '')
                          <img style="height: 5rem;" src="{{ $detail->image }}">
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Background image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image_background" data-preview="image_background-holder" data-type="cms-image"
                            class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i> @lang('Select')
                          </a>
                        </span>
                        <input id="image_background" class="form-control" type="text" name="image_background"
                          placeholder="@lang('Image source')" value="{{ $detail->image_background }}">
                      </div>
                      <div id="image_background-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image_background != '')
                          <img style="height: 5rem;" src="{{ $detail->image_background }}">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('List')
          </a>
          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
            @lang('Save')</button>
        </div>
      </form>
    </div>
  </section>

@endsection

@section('script')
  <script>
    $(document).ready(function() {
      $(document).on('change', '#block_code', function() {
        let block_code = $(this).val();
        var _targetLayout = $('#block_layout');
        var _targetStyle = $('#block_style');
        _targetLayout.html('');
        _targetStyle.html('');
        var url = "{{ route('blocks.params') }}/";
        $.ajax({
          type: "GET",
          url: url,
          data: {
            "block_code": block_code,
          },
          success: function(response) {
            var _optionListLayout = '<option value="">@lang("Please select")</option>';
            var _optionListStyle = '<option value="">@lang("Please select")</option>';
            if (response.data != null) {
              let json_params = JSON.parse(response.data);
              if (json_params.hasOwnProperty('layout')) {
                Object.entries(json_params.layout).forEach(([key, value]) => {
                  _optionListLayout += '<option value="' + value + '"> ' + value + ' </option>';
                });
              }
              _targetLayout.html(_optionListLayout);
              if (json_params.hasOwnProperty('style')) {
                Object.entries(json_params.style).forEach(([key, value]) => {
                  _optionListStyle += '<option value="' + value + '"> ' + value + ' </option>';
                });
              }
              _targetStyle.html(_optionListStyle);
            }
            $(".select2").select2();
          },
          error: function(response) {
            // Get errors
            var errors = response.responseJSON.message;
            console.log(errors);
          }
        });
      });
    });
  </script>
@endsection
