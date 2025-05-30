

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
       .d-flex {
            display: flex;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
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

        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            <?php echo app('translator')->get($module_name); ?>
                        </h3>
                        <a class="btn btn-sm btn-primary pull-right hide-print" href="<?php echo e(route('entry_warehouse')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('Danh sách phiếu'); ?>
                        </a>
                        <button class="btn btn-sm btn-warning pull-right hide-print mr-10" data-toggle="modal" data-target=".modal_payment_request_entry"><i
                                class="fa fa-plus"></i>
                            <?php echo app('translator')->get('Tạo phiếu thanh toán'); ?>
                        </button>
                        <a class="btn btn-sm btn-success pull-right hide-print mr-10" href="">
                            <i class="fa fa-refresh"></i> <?php echo app('translator')->get('Làm mới dữ liệu'); ?>
                        </a>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    <?php echo app('translator')->get('Cở sở'); ?>:
                                    <?php echo e($detail->area->name ?? ($detail->warehouse->area->name ?? '')); ?>

                                    / <?php echo e($detail->warehouse->name ?? ''); ?>

                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Kỳ'); ?>: <?php echo e($detail->period ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Tên phiếu'); ?>: <?php echo e($detail->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Mã phiếu'); ?>: <?php echo e($detail->code ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Người tạo phiếu'); ?>: <?php echo e($detail->admin_created->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày tạo phiếu'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? ''); ?></p>
                            </div>

                            <?php if(isset($detail->order_warehouse)): ?>
                                <div class="col-xs-6">
                                    <p>
                                        <?php echo app('translator')->get('Nhập theo phiếu'); ?>:
                                        <a target="_blank"
                                            href="<?php echo e(route('warehouse_order_product_buy.show', $detail->order_id)); ?>">
                                            <?php echo e($detail->order_warehouse->code . '-' . $detail->order_warehouse->name ?? ''); ?>

                                            <i class="fa fa-eye hide-print"></i>
                                        </a>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($detail->json_params->note) && $detail->json_params->note != ''): ?>
                                <div class="col-xs-6">
                                    <p><?php echo app('translator')->get('Ghi chú'); ?>: <?php echo e($detail->json_params->note ?? ''); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom:10px"><?php echo app('translator')->get('Danh sách sản phẩm nhập kho'); ?></h4>
                                <table id="myTable" class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr class="valign-middle">
                                            <th class="text-center" style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                            <th class="text-center" style="width:120px"><?php echo app('translator')->get('Mã sản phẩm'); ?></th>
                                            <th class="text-center"><?php echo app('translator')->get('Sản phẩm'); ?></th>
                                            <th class="text-center" style="width:120px"><?php echo app('translator')->get('Loại tài sản'); ?></th>
                                            <th class="text-center" style="width:75px"><?php echo app('translator')->get('ĐVT'); ?></th>
                                            <th class="text-center" style="width:75px"><?php echo app('translator')->get('Số lượng'); ?></th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('Đơn giá'); ?> <br /> (Dự kiến)
                                            </th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('Đơn giá'); ?> <br /> (Thực tế)
                                            </th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('Thành tiền'); ?></th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('VAT (%)'); ?></th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('Tiền thuế GTGT'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order">
                                        <?php if($entry_details->count() > 0): ?>
                                            <?php $__currentLoopData = $entry_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->product->code ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($entry_detail->product->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(__($entry_detail->product->warehouse_type) ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->product->unit ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->quantity ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(isset($entry_detail->product->price) && is_numeric($entry_detail->product->price) ? number_format($entry_detail->product->price, 0, ',', '.') : ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(isset($entry_detail->price) && is_numeric($entry_detail->price) ? number_format($entry_detail->price, 0, ',', '.') : ''); ?>

                                                    </td>
                                                    
                                                    <td>
                                                        <input type="hidden" class="subtotal-input" value="<?php echo e($entry_detail->subtotal_money ?? 0); ?>">
                                                        <span class="subtotal-text"><?php echo e(isset($entry_detail->subtotal_money) && is_numeric($entry_detail->subtotal_money) ? number_format($entry_detail->subtotal_money, 0, ',', '.') : ''); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <input data-detail-id="<?php echo e($entry_detail->id); ?>" value="<?php echo e($entry_detail->json_params->vat_money ?? 0); ?>"  
                                                                type="number" class="vat_value form-control" placeholder="VAT (%)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="vat_money"></p>
                                                    </td>
                                                    
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        <tr>
                                            <td colspan="8"><strong class="pull-right">Tổng : </strong></td>
                                            <td colspan="2">
                                                <input type="hidden" class="total-input" value="<?php echo e($detail->total_money ?? 0); ?>">
                                                <strong><?php echo e(isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . '' : ''); ?></strong>
                                            </td>
                                            <td ><strong class="total_vat">0</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"><strong class="pull-right">Tổng tiền: </strong></td>
                                            <td colspan="3">
                                                <strong class="total_money"><?php echo e(isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . '' : ''); ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php if($list_assets->count() > 0): ?>
                                <div class="col-md-12">
                                    <h4 class="box-title" style="padding-bottom:10px"><?php echo app('translator')->get('Danh sách tài sản tự động sinh mã theo phiếu nhập kho này'); ?></h4>

                                    <table class="table table-hover table-bordered sticky">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Mã Tài Sản'); ?></th>
                                                <th class="text-center"><?php echo app('translator')->get('Tên tài sản'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Loại tài sản'); ?></th>
                                                <th class="text-center" style="width:75px"><?php echo app('translator')->get('ĐVT'); ?></th>
                                                <th class="text-center" style="width:75px"><?php echo app('translator')->get('Số lượng'); ?></th>
                                                <th class="text-center" style="width:100px"><?php echo app('translator')->get('Đơn giá'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Phòng ban'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Vị trí'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order">

                                            <?php $__currentLoopData = $list_assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list_asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->code ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($list_asset->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(__($list_asset->product->warehouse_type) ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->product->unit ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->quantity ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(isset($list_asset->price) && is_numeric($list_asset->price) ? number_format($list_asset->price, 0, ',', '.') : ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->department->name ?? __('Chưa cập nhật')); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->position->name ?? __('Chưa cập nhật')); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>

                                    </table>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-12 show-print">
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    <?php echo app('translator')->get('Phòng HCNS'); ?>
                                </div>
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    <?php echo app('translator')->get('Thủ kho'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer hide-print">
                        <a class="btn btn-sm btn-success pull-right" target="_blank"
                            href="<?php echo e(route('warehouse_asset.index', ['entry_id' => $detail->id])); ?>">
                            <i class="fa fa-bank"></i> <?php echo app('translator')->get('Cập nhật thông tin lưu kho tài sản'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal_payment_request_entry" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Thông tin phiếu thanh toán
                            </h4>
                        </div>
                        <form  action="<?php echo e(route('payment_request_by_entry_store' )); ?>" method="POST" >
                            <?php echo csrf_field(); ?>
                            <input type="text" name="entry_id" value="<?php echo e($detail->id); ?>" hidden>
                            <input type="hidden" name="total_money_vnd" class="total_money_vnd" value="<?php echo e($detail->total_money); ?>" >
                            <input type="text" name="json_params[total_money_vnd_without_vat]" value="<?php echo e($detail->total_money ?? 0); ?>" hidden>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="box box-primary">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Người đề nghị thanh toán'); ?> </label>
                                                    <input type="text" class="form-control"
                                                    placeholder="<?php echo app('translator')->get('Name'); ?>" disabled value="<?php echo e($admin->name ??""); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Phòng ban'); ?> </label>
                                                    <select style="width:100%" class="form-control select2" name="dep_id">
                                                        <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option 
                                                            <?php echo e(isset($admin->department_id) && $admin->department_id == $dep->id ? "selected" : ""); ?> 
                                                            value="<?php echo e($dep->id); ?>"><?php echo e($dep->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Số tài khoản'); ?> </label>
                                                    <input name="qr_number" type="text" class="form-control"
                                                    placeholder="<?php echo app('translator')->get('Số tài khoản..'); ?>" value="<?php echo e(old('qr_number')); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Số tiền VNĐ đã tạm ứng'); ?></label>
                                                    <div class="d-flex">
                                                        <input value="<?php echo e(old('total_money_vnd_advance') ?? 0); ?>" name="total_money_vnd_advance" type="number" class="form-control" placeholder="<?php echo app('translator')->get('Số tiền vnđ đã tạm ứng..'); ?>">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Nội dung'); ?> <small class="text-red">*</small></label>
                                                    <textarea class="form-control" name="content"
                                                    placeholder="<?php echo app('translator')->get('Nội dung đề nghị'); ?>" required>Đề nghị thanh toán cho phiếu nhập kho <?php echo e($detail->name??""); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary print_payment">
                                    <?php echo app('translator')->get('Tạo đề nghị thanh toán'); ?>
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <?php echo app('translator')->get('Đóng'); ?>
                                </button>
                            </div>
                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '';
    }

    function calculateTotal() {
        let totalSubtotal = $('.total-input').val() || 0;
        let totalVat = 0;

        $('#myTable tr').each(function() {
            const subtotal = Number($(this).find('.subtotal-input').val())||0;
            const vatPercent = Number($(this).find('.vat_value').val())||0;
            const vatMoney = Number((subtotal * vatPercent) / 100);

            totalVat +=vatMoney;

            $(this).find('.vat_money').text(formatCurrency(vatMoney));
        });
        let totalAfterTax = Number(totalSubtotal)  + Number(totalVat) ;

        $('.total_vat').text(formatCurrency(totalVat));
        $('.total_money').text(formatCurrency(totalAfterTax));
        $('.total_money_vnd').val(totalAfterTax);
    }

    $('#myTable').on('input', function() {
        calculateTotal();
    });

    $(document).ready(function() {
        calculateTotal();
    });

    $('.vat_value').change(function (e) { 
        var _id=$(this).attr('data-detail-id');
        var _value=$(this).val();
        let _url = "<?php echo e(route('ajax_update_vat_entry_detail')); ?>";
        $.ajax({
            type: "GET",
            url: _url,
            data: {
                id: _id,
                vat_money: _value,
            },
            success: function(response) {
                
            },
            error: function(response) {
                let errors = response.responseJSON.message;
                alert(errors);
            }
        });
    });
    
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_entry/show.blade.php ENDPATH**/ ?>