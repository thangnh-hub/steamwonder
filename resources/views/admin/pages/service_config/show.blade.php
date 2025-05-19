<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">
                <h5 class="fw-bold">Thông tin chính</h5>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong>@lang('Khu vực')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p>{{ $detail->area->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong>@lang('Loại phí dịch vụ')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p>{{ __($detail->type) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong>@lang('Áp dựng từ')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p>{{ optional(\Carbon\Carbon::parse($detail->time_start))->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong>@lang('Áp dụng đến')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p>{{ optional(\Carbon\Carbon::parse($detail->time_end))->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong>@lang('Thời gian')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                @if (isset($detail->json_params->time_range) && count((array) $detail->json_params->time_range) > 0)
                                    <ul>
                                        @foreach ((array) $detail->json_params->time_range as $key => $val)
                                            <li>
                                                Từ:
                                                {{ $val->block_start != '' ? optional(\Carbon\Carbon::parse($val->block_start))->format('H:i') : '' }}
                                                 - Đến:{{ $val->block_end != '' ? optional(\Carbon\Carbon::parse($val->block_end))->format('H:i') : '' }}
                                                - Phí:{{ number_format($val->price, 0, ',', '.') ?? '' }} đ
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Ngày tạo')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ date('H:i - d/m/Y', strtotime($detail->created_at)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Người tạo')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ $detail->adminCreated->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Ngày cập nhật')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ date('H:i - d/m/Y', strtotime($detail->updated_at)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Người cập nhật')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ $detail->adminUpdated->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
