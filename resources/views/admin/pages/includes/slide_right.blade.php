<div class="col-lg-4">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Publish')</h3>
        </div>
        <div class="box-body">
            <div class="btn-set">


                <button type="submit" class="btn btn-info">
                    <i class="fa fa-save"></i> @lang('Save')
                </button>
                &nbsp;&nbsp;
                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Status')</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <select name="status" class=" form-control select2">
                    @foreach ($status as $key => $val)
                        <option value="{{ $key }}"
                            {{ (isset($detail->status) && $detail->status == $val) ? 'checked' : '' }}>@lang($val)</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border sw_featured d-flex-al-center">
            <label class="switch ">
                <input id="sw_featured" name="is_featured" value="1" type="checkbox"
                    {{ (isset($detail->is_featured) && $detail->is_featured == '1') ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
            <label class="box-title ml-1" for="sw_featured">@lang('Is featured')</label>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Order')</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <input type="number" class="form-control" name="iorder"
                    placeholder="@lang('Order')" value="{{ $detail->iorder??old('iorder') }}">
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Categories') <small class="text-red">*</small></h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <ul class="list-relation">
                    @foreach ($parents as $item)
                        @if ($item->parent_id == 0 || $item->parent_id == null)
                            <li>
                                <label for="page-{{ $item->id }}">
                                    <input id="page-{{ $item->id }}" name="relation[]"
                                        {{(isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item->id)!=null)?'checked':''}}

                                        type="checkbox" value="{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                                <ul class="list-relation">
                                    @foreach ($parents as $item1)
                                        @if ($item1->parent_id == $item->id)
                                            <li>
                                                <label for="page-{{ $item1->id }}">
                                                    <input id="page-{{ $item1->id }}"
                                                        name="relation[]" type="checkbox"
                                                        {{(isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item1->id)!=null)?'checked':''}}
                                                        value="{{ $item1->id }}">
                                                    {{ $item1->name }}
                                                </label>
                                                <ul class="list-relation">
                                                    @foreach ($parents as $item2)
                                                        @if ($item2->parent_id == $item1->id)
                                                            <li>
                                                                <label for="page-{{ $item2->id }}">
                                                                    <input id="page-{{ $item2->id }}"
                                                                        name="relation[]" type="checkbox"
                                                                        {{(isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item2->id)!=null)?'checked':''}}
                                                                        value="{{ $item2->id }}">
                                                                    {{ $item2->name }}
                                                                </label>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Image')</h3>
        </div>
        <div class="box-body">
            <div class="form-group box_img_right">
                <div id="image-holder" class="box_image {{isset($detail->image)?"active":""}}">
                    <img class="img-width"
                        src="{{ $detail->image ?? url('themes/admin/img/no_image.jpg') }}">
                    <input id="image" class="form-control hidden list_image" type="text"
                        name="image" value="{{$detail->image??""}}">
                    <span class="btn btn-sm btn-danger btn-remove" style="display: none"><i
                            class="fa fa-trash"></i></span>
                </div>
                <span class="input-group-btn">
                    <a data-input="image" class="btn btn-primary lfm" data-type="cms-image">
                        <i class="fa fa-picture-o"></i> @lang('choose')
                    </a>
                </span>


            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Image thumb')</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <div class="form-group box_img_right">
                    <div id="image-holder" class="box_image {{isset($detail->image_thumb)?"active":""}}">
                        <img class="img-width"
                            src="{{ $detail->image_thumb ?? url('themes/admin/img/no_image.jpg') }}">
                        <input id="image_thumb" class="form-control hidden list_image" type="text"
                            name="image_thumb" value="{{$detail->image_thumb??""}}">
                        <span class="btn btn-sm btn-danger btn-remove" style="display: none"><i
                                class="fa fa-trash"></i></span>
                    </div>
                    <span class="input-group-btn">
                        <a data-input="image_thumb" class="btn btn-primary lfm" data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                        </a>
                    </span>

                </div>

            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Page config')</h3>
        </div>
        <div class="box-body">

                <div class="form-group">
                    <label>@lang('Route Name')</label>
                    <small class="text-red">*</small>
                    <select name="json_params[route_name]" id="route_name" class="form-control select2"
                        style="width:100%" required autocomplete="off">
                        <option value="">@lang('Please select')</option>
                        @foreach ($route_name as $key => $item)
                            <option value="{{ $item['name'] }}"
                                {{ isset($detail->json_params->route_name) && $detail->json_params->route_name == $item['name'] ? 'selected' : '' }}>
                                {{ __($item['title']) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            @php
                $route = $detail->json_params->route_name ?? '';
                $templates = collect(App\Consts::ROUTE_NAME);
                $template = $templates->first(function ($item, $key) use ($route) {
                    return $item['name'] == $route;
                });
            @endphp
                <div class="form-group">
                    <label>@lang('Template')</label>
                    <small class="text-red">*</small>
                    <select name="json_params[template]" id="template" class="form-control select2"
                        style="width:100%" required autocomplete="off">
                        <option value="">@lang('Please select')</option>
                        @isset($template['template'])
                            @foreach ($template['template'] as $key => $item)
                                <option value="{{ $item['name'] }}"
                                    {{ isset($detail->json_params->template) && $detail->json_params->template == $item['name'] ? 'selected' : '' }}>
                                    {{ __($item['title']) }}
                                </option>
                            @endforeach
                        @endisset

                    </select>
                </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Widgets config')</h3>
        </div>
        <div class="box-body">
            @foreach ($widgetConfig as $val)
                <div class="form-group">
                <label>{{$val->name}}</label>
                <select name="widget[]" class=" form-control select2">
                    <option value="0">@lang('Please select')</option>
                    @foreach ($widgets as $val_wg)
                        @if ($val_wg->widget_code == $val->widget_code)
                            <option value="{{ $val_wg->id }}" {{ isset($detail->json_params->widget) && in_array($val_wg->id,$detail->json_params->widget) ? 'selected' : '' }}>@lang($val_wg->title)
                        </option>
                        @endif

                    @endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>
</div>
