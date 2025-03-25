@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .d-flex{
            display: flex;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>

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

        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-success btn-sm pull-right">
                                    @lang('Danh sách')
                                </button>
                            </a>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính </h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Người đề nghị thanh toán') </label>
                                                    <input type="text" class="form-control"
                                                    placeholder="@lang('Name')" disabled value="{{ $detail->user->name??"" }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Phòng ban') </label>
                                                    <select class="form-control select2" name="dep_id">
                                                        @foreach ($department as $dep)
                                                            <option 
                                                            {{ isset($detail->dep_id) && $detail->dep_id == $dep->id ? "selected" : "" }} 
                                                            value="{{ $dep->id }}">{{ $dep->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số tài khoản') </label>
                                                    <input name="qr_number" type="text" class="form-control"
                                                    placeholder="@lang('Số tài khoản..')" value="{{ $detail->qr_number ??"" }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số tiền VNĐ đã tạm ứng')</label>
                                                    <div class="d-flex">
                                                        <input value="{{ $detail->total_money_vnd_advance ?? 0 }}" name="total_money_vnd_advance" type="number" class="form-control" placeholder="@lang('Số tiền vnđ đã tạm ứng..')">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số tiền EURO đã tạm ứng')</label>
                                                    <div class="d-flex">
                                                        <input value="{{ $detail->total_money_euro_advance ?? 0 }}" name="total_money_euro_advance" type="number" class="form-control" placeholder="@lang('Số tiền euro đã tạm ứng..')">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Nội dung') <small class="text-red">*</small></label>
                                                    <textarea class="form-control" name="content"
                                                    placeholder="@lang('Nội dung đề nghị')" required>{{ $detail->content ?? old('content') }}</textarea>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($detail->is_entry==0)
            <section class="mb-15 box_alert">
                <h3>
                    @lang('Danh sách khoản thanh toán')
                </h3>
            </section>

            
            <div class="box-avaible-payment-detail">
                @if (isset($paymentRequestDetail) && count($paymentRequestDetail) > 0)
                    @foreach ($paymentRequestDetail as $key => $payment_detail)
                        <div class="row khoan-item">
                            <div class="col-lg-12">
                                <div class="box box-primary ">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">@lang('Khoản thanh toán') {{ $loop->index + 1 }}</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" onclick="delete_lesson(this)" class="btn btn-sm btn-danger" ><i class="fa fa-recycle "></i> Xóa khoản thanh toán</button>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="nav-tabs-custom">
                                            <div class="tab_offline">
                                                <div class="tab-pane active">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Hình thức')</label>
                                                            <select style="width:100%" class="form-control select2" name="payment_detail[{{ $key }}][type_payment]">
                                                                @foreach ($type_khoan as $k=> $type)
                                                                    <option 
                                                                    {{ isset($payment_detail->type_payment) && $payment_detail->type_payment == $k ? "selected" : "" }} 
                                                                    value="{{ $k }}">{{ __($type) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Ngày phát sinh')</label>
                                                            <input required type="date" class="form-control" name="payment_detail[{{ $key }}][date_arise]"
                                                            placeholder="@lang('Ngày phát sinh')"  value="{{ $payment_detail->date_arise ?? date("Y-m-d",time()) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Số chứng từ')</label>
                                                            <input type="text" class="form-control" name="payment_detail[{{ $key }}][doc_number]"
                                                            placeholder="@lang('Số chứng từ')" value="{{ $payment_detail->doc_number ?? "" }}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Nội dung') <small class="text-red">*</small></label>
                                                            <textarea required class="form-control" name="payment_detail[{{ $key }}][content]"
                                                            placeholder="@lang('Nội dung')">{{ $payment_detail->content ?? "" }}</textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Số lượng') <small class="text-red">*</small></label>
                                                            <input name="payment_detail[{{ $key }}][quantity]" type="number" class="form-control"
                                                            placeholder="@lang('Số lượng..')" value="{{ $payment_detail->quantity ?? 1 }}" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Số lần cần thanh toán') <small class="text-red">*</small></label>
                                                            <input name="payment_detail[{{ $key }}][number_times]" type="number" class="form-control"
                                                            placeholder="@lang('Số lần cần thanh toán..')" value="{{ $payment_detail->number_times ?? 1 }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Đơn giá (VNĐ)') </label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[{{ $key }}][price_vnd]" type="number" class="form-control"
                                                                placeholder="@lang('Đơn giá vnđ..')" value="{{ $payment_detail->price_vnd ?? "" }}">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Tiền VAT 10%') (VNĐ)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[{{ $key }}][vat_10_number_vnd]" type="number" class="form-control"
                                                                placeholder="@lang('VAT 10%..')" value="{{ $payment_detail->vat_10_number_vnd ?? 0 }}">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Tiền VAT 8%') (VNĐ)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[{{ $key }}][vat_8_number_vnd]" type="number" class="form-control"
                                                                placeholder="@lang('VAT 8%..')" value="{{ $payment_detail->vat_8_number_vnd ?? 0 }}">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Đơn giá euro') </label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[{{ $key }}][price_euro]" type="number" class="form-control"
                                                                placeholder="@lang('Đơn giá euro..')" value="{{ $payment_detail->price_euro ?? "" }}">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Tiền VAT 10%') (EURO)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[{{ $key }}][vat_10_number_euro]" type="number" class="form-control"
                                                                placeholder="@lang('VAT 10%..')" value="{{ $payment_detail->vat_10_number_euro ?? 0 }}">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>@lang('Tiền VAT 8%') (EURO)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[{{ $key }}][vat_8_number_euro]" type="number" class="form-control"
                                                                placeholder="@lang('VAT 8%..')" value="{{ $payment_detail->vat_8_number_euro ?? 0 }}">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Ghi chú') </label>
                                                            <textarea class="form-control" name="payment_detail[{{ $key }}][note]"
                                                            placeholder="@lang('Ghi chú')">{{ $payment_detail->note ?? "" }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @endif
            <section class="mb-15 pl-0">
                @if($detail->is_entry==0)
                <button type="button" class="btn btn-primary add-payment-detail"><i class="fa fa-plus"></i>
                    @lang('Thêm khoản')
                </button>
                @endif
                <button type="submit" class="btn btn-info pull-right">
                    <i class="fa fa-save"></i> @lang('Save')
                </button>
            </section>
        </form>
    </section>
@endsection

@section('script')
    @if($detail->is_entry==0)
        <script>
            function delete_lesson(th) {
                $(th).parents('.khoan-item').remove();
            }

            $('.add-payment-detail').click(function() {
                var currentTime = $.now();
                var countLesson = $("div.khoan-item").length + 1;
                var _targetHTML = `<div class="row khoan-item">
                                <div class="col-lg-12">
                                    <div class="box box-primary ">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">@lang('Khoản thanh toán') ${countLesson}</h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" onclick="delete_lesson(this)" class="btn btn-sm btn-danger" ><i class="fa fa-recycle "></i> Xóa khoản thanh toán</button>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="nav-tabs-custom">
                                                <div class="tab_offline">
                                                    <div class="tab-pane active">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Hình thức')</label>
                                                                <select style="width:100%" class="form-control select2" name="payment_detail[${currentTime}][type_payment]">
                                                                    @foreach ($type_khoan as $k=> $type)
                                                                        <option 
                                                                        value="{{ $k }}">{{ __($type) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Ngày phát sinh')</label>
                                                                <input required type="date" class="form-control" name="payment_detail[${currentTime}][date_arise]"
                                                                placeholder="@lang('Ngày phát sinh')"  value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Số chứng từ')</label>
                                                                <input type="text" class="form-control" name="payment_detail[${currentTime}][doc_number]"
                                                                placeholder="@lang('Số chứng từ')" value="">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>@lang('Nội dung') <small class="text-red">*</small></label>
                                                                <textarea required class="form-control" name="payment_detail[${currentTime}][content]"
                                                                placeholder="@lang('Nội dung')"></textarea>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Số lượng') <small class="text-red">*</small></label>
                                                                <input name="payment_detail[${currentTime}][quantity]" type="number" class="form-control"
                                                                placeholder="@lang('Số lượng..')" value="" required>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Số lần cần thanh toán') <small class="text-red">*</small></label>
                                                                <input name="payment_detail[${currentTime}][number_times]" type="number" class="form-control"
                                                                placeholder="@lang('Số lần cần thanh toán..')" value="" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Đơn giá (VNĐ)') </label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][price_vnd]" type="number" class="form-control"
                                                                    placeholder="@lang('Đơn giá vnđ..')" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Tiền VAT 10%') (VNĐ)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_10_number_vnd]" type="number" class="form-control"
                                                                    placeholder="@lang('VAT 10%..')" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Tiền VAT 8%') (VNĐ)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_8_number_vnd]" type="number" class="form-control"
                                                                    placeholder="@lang('VAT 8%..')" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Đơn giá euro') </label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][price_euro]" type="number" class="form-control"
                                                                    placeholder="@lang('Đơn giá euro..')" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Tiền VAT 10%') (EURO)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_10_number_euro]" type="number" class="form-control"
                                                                    placeholder="@lang('VAT 10%..')" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>@lang('Tiền VAT 8%') (EURO)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_8_number_euro]" type="number" class="form-control"
                                                                    placeholder="@lang('VAT 8%..')" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>@lang('Ghi chú') </label>
                                                                <textarea class="form-control" name="payment_detail[${currentTime}][note]"
                                                                placeholder="@lang('Ghi chú')"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $('.box-avaible-payment-detail').append(_targetHTML);
                $('.select2').select2();
            });
        </script>
@endif
@endsection
