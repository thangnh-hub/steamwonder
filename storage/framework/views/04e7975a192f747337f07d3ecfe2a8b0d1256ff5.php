

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .d-flex{
            display: flex;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>

    <section class="content">
        <?php if(session('errorMessage')): ?>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('errorMessage')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('successMessage')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('successMessage')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        <?php endif; ?>

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Update form'); ?></h3>
                            <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <button type="button" class="btn btn-success btn-sm pull-right">
                                    <?php echo app('translator')->get('Danh sách'); ?>
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
                                                    <label><?php echo app('translator')->get('Người đề nghị thanh toán'); ?> </label>
                                                    <input type="text" class="form-control"
                                                    placeholder="<?php echo app('translator')->get('Name'); ?>" disabled value="<?php echo e($detail->user->name??""); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Phòng ban'); ?> </label>
                                                    <select class="form-control select2" name="dep_id">
                                                        <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option 
                                                            <?php echo e(isset($detail->dep_id) && $detail->dep_id == $dep->id ? "selected" : ""); ?> 
                                                            value="<?php echo e($dep->id); ?>"><?php echo e($dep->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Số tài khoản'); ?> </label>
                                                    <input name="qr_number" type="text" class="form-control"
                                                    placeholder="<?php echo app('translator')->get('Số tài khoản..'); ?>" value="<?php echo e($detail->qr_number ??""); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Số tiền VNĐ đã tạm ứng'); ?></label>
                                                    <div class="d-flex">
                                                        <input value="<?php echo e($detail->total_money_vnd_advance ?? 0); ?>" name="total_money_vnd_advance" type="number" class="form-control" placeholder="<?php echo app('translator')->get('Số tiền vnđ đã tạm ứng..'); ?>">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Số tiền EURO đã tạm ứng'); ?></label>
                                                    <div class="d-flex">
                                                        <input value="<?php echo e($detail->total_money_euro_advance ?? 0); ?>" name="total_money_euro_advance" type="number" class="form-control" placeholder="<?php echo app('translator')->get('Số tiền euro đã tạm ứng..'); ?>">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Nội dung'); ?> <small class="text-red">*</small></label>
                                                    <textarea class="form-control" name="content"
                                                    placeholder="<?php echo app('translator')->get('Nội dung đề nghị'); ?>" required><?php echo e($detail->content ?? old('content')); ?></textarea>
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
            <?php if($detail->is_entry==0): ?>
            <section class="mb-15 box_alert">
                <h3>
                    <?php echo app('translator')->get('Danh sách khoản thanh toán'); ?>
                </h3>
            </section>

            
            <div class="box-avaible-payment-detail">
                <?php if(isset($paymentRequestDetail) && count($paymentRequestDetail) > 0): ?>
                    <?php $__currentLoopData = $paymentRequestDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payment_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row khoan-item">
                            <div class="col-lg-12">
                                <div class="box box-primary ">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo app('translator')->get('Khoản thanh toán'); ?> <?php echo e($loop->index + 1); ?></h3>
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
                                                            <label><?php echo app('translator')->get('Hình thức'); ?></label>
                                                            <select style="width:100%" class="form-control select2" name="payment_detail[<?php echo e($key); ?>][type_payment]">
                                                                <?php $__currentLoopData = $type_khoan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option 
                                                                    <?php echo e(isset($payment_detail->type_payment) && $payment_detail->type_payment == $k ? "selected" : ""); ?> 
                                                                    value="<?php echo e($k); ?>"><?php echo e(__($type)); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Ngày phát sinh'); ?></label>
                                                            <input required type="date" class="form-control" name="payment_detail[<?php echo e($key); ?>][date_arise]"
                                                            placeholder="<?php echo app('translator')->get('Ngày phát sinh'); ?>"  value="<?php echo e($payment_detail->date_arise ?? date("Y-m-d",time())); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số chứng từ'); ?></label>
                                                            <input type="text" class="form-control" name="payment_detail[<?php echo e($key); ?>][doc_number]"
                                                            placeholder="<?php echo app('translator')->get('Số chứng từ'); ?>" value="<?php echo e($payment_detail->doc_number ?? ""); ?>">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Nội dung'); ?> <small class="text-red">*</small></label>
                                                            <textarea required class="form-control" name="payment_detail[<?php echo e($key); ?>][content]"
                                                            placeholder="<?php echo app('translator')->get('Nội dung'); ?>"><?php echo e($payment_detail->content ?? ""); ?></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số lượng'); ?> <small class="text-red">*</small></label>
                                                            <input name="payment_detail[<?php echo e($key); ?>][quantity]" type="number" class="form-control"
                                                            placeholder="<?php echo app('translator')->get('Số lượng..'); ?>" value="<?php echo e($payment_detail->quantity ?? 1); ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số lần cần thanh toán'); ?> <small class="text-red">*</small></label>
                                                            <input name="payment_detail[<?php echo e($key); ?>][number_times]" type="number" class="form-control"
                                                            placeholder="<?php echo app('translator')->get('Số lần cần thanh toán..'); ?>" value="<?php echo e($payment_detail->number_times ?? 1); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Đơn giá (VNĐ)'); ?> </label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[<?php echo e($key); ?>][price_vnd]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('Đơn giá vnđ..'); ?>" value="<?php echo e($payment_detail->price_vnd ?? ""); ?>">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Tiền VAT 10%'); ?> (VNĐ)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[<?php echo e($key); ?>][vat_10_number_vnd]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('VAT 10%..'); ?>" value="<?php echo e($payment_detail->vat_10_number_vnd ?? 0); ?>">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Tiền VAT 8%'); ?> (VNĐ)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[<?php echo e($key); ?>][vat_8_number_vnd]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('VAT 8%..'); ?>" value="<?php echo e($payment_detail->vat_8_number_vnd ?? 0); ?>">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Đơn giá euro'); ?> </label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[<?php echo e($key); ?>][price_euro]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('Đơn giá euro..'); ?>" value="<?php echo e($payment_detail->price_euro ?? ""); ?>">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Tiền VAT 10%'); ?> (EURO)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[<?php echo e($key); ?>][vat_10_number_euro]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('VAT 10%..'); ?>" value="<?php echo e($payment_detail->vat_10_number_euro ?? 0); ?>">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Tiền VAT 8%'); ?> (EURO)</label>
                                                            <div class="d-flex">
                                                                <input name="payment_detail[<?php echo e($key); ?>][vat_8_number_euro]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('VAT 8%..'); ?>" value="<?php echo e($payment_detail->vat_8_number_euro ?? 0); ?>">
                                                                <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Ghi chú'); ?> </label>
                                                            <textarea class="form-control" name="payment_detail[<?php echo e($key); ?>][note]"
                                                            placeholder="<?php echo app('translator')->get('Ghi chú'); ?>"><?php echo e($payment_detail->note ?? ""); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <section class="mb-15 pl-0">
                <?php if($detail->is_entry==0): ?>
                <button type="button" class="btn btn-primary add-payment-detail"><i class="fa fa-plus"></i>
                    <?php echo app('translator')->get('Thêm khoản'); ?>
                </button>
                <?php endif; ?>
                <button type="submit" class="btn btn-info pull-right">
                    <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                </button>
            </section>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <?php if($detail->is_entry==0): ?>
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
                                            <h3 class="box-title"><?php echo app('translator')->get('Khoản thanh toán'); ?> ${countLesson}</h3>
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
                                                                <label><?php echo app('translator')->get('Hình thức'); ?></label>
                                                                <select style="width:100%" class="form-control select2" name="payment_detail[${currentTime}][type_payment]">
                                                                    <?php $__currentLoopData = $type_khoan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option 
                                                                        value="<?php echo e($k); ?>"><?php echo e(__($type)); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Ngày phát sinh'); ?></label>
                                                                <input required type="date" class="form-control" name="payment_detail[${currentTime}][date_arise]"
                                                                placeholder="<?php echo app('translator')->get('Ngày phát sinh'); ?>"  value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Số chứng từ'); ?></label>
                                                                <input type="text" class="form-control" name="payment_detail[${currentTime}][doc_number]"
                                                                placeholder="<?php echo app('translator')->get('Số chứng từ'); ?>" value="">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Nội dung'); ?> <small class="text-red">*</small></label>
                                                                <textarea required class="form-control" name="payment_detail[${currentTime}][content]"
                                                                placeholder="<?php echo app('translator')->get('Nội dung'); ?>"></textarea>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Số lượng'); ?> <small class="text-red">*</small></label>
                                                                <input name="payment_detail[${currentTime}][quantity]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('Số lượng..'); ?>" value="" required>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Số lần cần thanh toán'); ?> <small class="text-red">*</small></label>
                                                                <input name="payment_detail[${currentTime}][number_times]" type="number" class="form-control"
                                                                placeholder="<?php echo app('translator')->get('Số lần cần thanh toán..'); ?>" value="" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Đơn giá (VNĐ)'); ?> </label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][price_vnd]" type="number" class="form-control"
                                                                    placeholder="<?php echo app('translator')->get('Đơn giá vnđ..'); ?>" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Tiền VAT 10%'); ?> (VNĐ)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_10_number_vnd]" type="number" class="form-control"
                                                                    placeholder="<?php echo app('translator')->get('VAT 10%..'); ?>" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Tiền VAT 8%'); ?> (VNĐ)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_8_number_vnd]" type="number" class="form-control"
                                                                    placeholder="<?php echo app('translator')->get('VAT 8%..'); ?>" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Đơn giá euro'); ?> </label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][price_euro]" type="number" class="form-control"
                                                                    placeholder="<?php echo app('translator')->get('Đơn giá euro..'); ?>" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Tiền VAT 10%'); ?> (EURO)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_10_number_euro]" type="number" class="form-control"
                                                                    placeholder="<?php echo app('translator')->get('VAT 10%..'); ?>" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Tiền VAT 8%'); ?> (EURO)</label>
                                                                <div class="d-flex">
                                                                    <input name="payment_detail[${currentTime}][vat_8_number_euro]" type="number" class="form-control"
                                                                    placeholder="<?php echo app('translator')->get('VAT 8%..'); ?>" value="">
                                                                    <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Ghi chú'); ?> </label>
                                                                <textarea class="form-control" name="payment_detail[${currentTime}][note]"
                                                                placeholder="<?php echo app('translator')->get('Ghi chú'); ?>"></textarea>
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
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/payment_request/edit.blade.php ENDPATH**/ ?>