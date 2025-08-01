

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        .table-bordered>thead:first-child>tr:first-child>th {
            text-align: center;
            vertical-align: middle;
        }
        .text-center{
            text-align: center !important;
        }
        @media  print {
            #printButton,
            .hide-print {
                display: none !important;
                /* Ẩn nút khi in */
            }
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
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
        <div class="box">
            <div class="show-print">
                <h3 class="box-title text-uppercase text-center w-100">Cộng hòa xã hội chủ nghĩa Việt Nam</h3>
                <h3 class="box-title text-center w-100">Độc lập - Tự do - Hạnh phúc</h3>

                <p class="text-right"><i>Hà Nội, ngày <?php echo e(date('d'), time()); ?> Tháng <?php echo e(date('m'), time()); ?> Năm
                    <?php echo e(date('Y'), time()); ?></i></p>

                <div class="text-center">
                    <h3 class="text-uppercase mt-15">Đề nghị thanh toán</h3>
                    <p class="fw-bold">Kính gửi: <span class="text-uppercase">Ban giám đốc</span></p>
                </div>
            </div>
            <div class="box-header  text-center">
                <h4 class="box-title text-uppercase text-bold hide-print"><?php echo e($module_name); ?></h4>
                <button onclick="window.print()" class="btn btn-sm btn-warning pull-right hide-print mr-10"><i class="fa fa-print"></i>
                    <?php echo app('translator')->get('In phiếu đề nghị thanh toán'); ?></button>
            </div>
            
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <p><?php echo app('translator')->get('Họ tên người thanh toán'); ?>: <strong><?php echo e($detail->user->name ?? ''); ?></strong></p>
                    </div>
                    <div class="col-xs-12">
                        <p><?php echo app('translator')->get('Phòng ban/Bộ phận'); ?>: <strong><?php echo e($detail->department->name ?? ''); ?></strong></p>
                    </div>
                    <div class="col-xs-12">
                        <p><?php echo app('translator')->get('Nội dung'); ?>: <strong><?php echo e($detail->content ?? ''); ?></strong></p>
                    </div>
                    <div class="col-xs-12">
                        <p><?php echo app('translator')->get('Số tài khoản'); ?>: <strong><?php echo e($detail->qr_number ?? ''); ?></strong></p>
                    </div>
                    
                    <?php if(isset($paymentRequestDetail) && count($paymentRequestDetail) > 0): ?>
                        <div class="col-md-12">
                            <h4 style="padding-bottom: 10px;margin-top:20px" class="hide-print"><?php echo app('translator')->get('Danh sách các khoản thanh toán'); ?></h4>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Ngày phát sinh'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Số chứng từ'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Diễn giải nội dung'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Số lượng'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get(''); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Loại'); ?></th>
                                        <th class="text-center" colspan="2"><?php echo app('translator')->get('Đơn giá'); ?></th>
                                        <th class="text-center" colspan="2"><?php echo app('translator')->get('Thành tiền'); ?></th>
                                        <th class="text-center"  rowspan="2"><?php echo app('translator')->get('Ghi chú'); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><?php echo app('translator')->get('VNĐ'); ?></th>
                                        <th class="text-center"><?php echo app('translator')->get('EURO'); ?></th>
                                        <th style="width:150px" class="text-center"><?php echo app('translator')->get('VNĐ'); ?></th>
                                        <th style="width:150px" class="text-center"><?php echo app('translator')->get('EURO'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-order">
                                    <?php $__currentLoopData = $paymentRequestDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payment_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="valign-middle">
                                            <td class="text-center">
                                                <?php echo e($loop->index + 1); ?>

                                            </td>
                                            <td>
                                                <?php echo e(isset($payment_detail->date_arise) && $payment_detail->date_arise!= "" ? date("Y-m-d",time()) :""); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e($payment_detail->doc_number ?? ""); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e($payment_detail->content ?? ""); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e($payment_detail->quantity ?? ""); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e($payment_detail->number_times ?? ""); ?>

                                            </td>
                                            <td>
                                                <?php echo e(__($payment_detail->type_payment ?? "")); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(isset($payment_detail->price_vnd) && is_numeric($payment_detail->price_vnd) ? number_format($payment_detail->price_vnd, 0, ',', '.') : ''); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(isset($payment_detail->price_euro) && is_numeric($payment_detail->price_euro) ? number_format($payment_detail->price_euro, 0, ',', '.') : ''); ?>

                                            </td>

                                            <td class="text-center">
                                                <?php echo e(isset($payment_detail->money_vnd) && is_numeric($payment_detail->money_vnd) ? number_format($payment_detail->money_vnd, 0, ',', '.') : ''); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(isset($payment_detail->money_euro) && is_numeric($payment_detail->money_euro) ? number_format($payment_detail->money_euro, 0, ',', '.') : ''); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e($payment_detail->note ?? ""); ?>

                                            </td>                                           
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="vertical-align: middle;" class="text-bold " colspan="9">TỔNG CỘNG TRƯỚC THUẾ:</td>
                                        <td class="text-bold text-center">
                                            <p><?php echo e(isset($detail->total_money_vnd) && is_numeric($detail->total_money_vnd) ? number_format($detail->total_money_vnd, 0, ',', '.') : ''); ?> VNĐ</p>   
                                        </td>
                                        <td class="text-bold text-center">
                                            <p><?php echo e(isset($detail->total_money_euro) && is_numeric($detail->total_money_euro) ? number_format($detail->total_money_euro, 0, ',', '.') : ''); ?> EURO</p>   
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" class="text-bold " colspan="9">VAT 8%:</td>
                                        <td class="text-bold text-center">
                                            <p><?php echo e(isset($detail->total_vat_8_vnd) && is_numeric($detail->total_vat_8_vnd) ? number_format($detail->total_vat_8_vnd, 0, ',', '.') : '0'); ?> VNĐ</p> 
                                        </td>
                                        <td class="text-bold text-center">
                                            <p><?php echo e(isset($detail->total_vat_8_euro) && is_numeric($detail->total_vat_8_euro) ? number_format($detail->total_vat_8_euro, 0, ',', '.') : '0'); ?> EURO</p> 
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" class="text-bold " colspan="9">VAT 10%:</td>
                                        <td class="text-bold text-center">
                                            <p><?php echo e(isset($detail->total_vat_10_vnd) && is_numeric($detail->total_vat_10_vnd) ? number_format($detail->total_vat_10_vnd, 0, ',', '.') : '0'); ?> VNĐ</p> 
                                        </td>
                                        <td class="text-bold text-center">
                                            <p><?php echo e(isset($detail->total_vat_10_euro) && is_numeric($detail->total_vat_10_euro) ? number_format($detail->total_vat_10_euro, 0, ',', '.') : '0'); ?> EURO</p>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" class="text-bold " colspan="9">TỔNG TIỀN:</td>
                                        <td class="text-bold text-center" >
                                            <p><?php echo e(isset($total_money_vnd_before_vat) && is_numeric($total_money_vnd_before_vat) ? number_format($total_money_vnd_before_vat, 0, ',', '.') : ''); ?> VNĐ</p>   
                                        </td>
                                        <td class="text-bold text-center" >
                                            <p><?php echo e(isset($total_money_euro_before_vat) && is_numeric($total_money_euro_before_vat) ? number_format($total_money_euro_before_vat, 0, ',', '.') : ''); ?> EURO</p>   
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6"><p>Số tiền đã tạm ứng (VNĐ):</p></div>
                    <div class="col-md-6"><p><strong><?php echo e(isset($detail->total_money_vnd_advance) && is_numeric($detail->total_money_vnd_advance) ? number_format($detail->total_money_vnd_advance, 0, ',', '.') : '0'); ?> VNĐ</strong></p></div>

                    <div class="col-md-6"><p>Số tiền đã tạm ứng (EURO):</p></div>
                    <div class="col-md-6"><p><strong><?php echo e(isset($detail->total_money_euro_advance) && is_numeric($detail->total_money_euro_advance) ? number_format($detail->total_money_euro_advance, 0, ',', '.') : '0'); ?> EURO</strong></p></div>
                    
                    <?php if(isset($paymentRequestDetail) && count($paymentRequestDetail) > 0): ?>
                        <div class="col-md-6"><p>Số tiền cần thanh toán (VNĐ):</p></div>
                        <div class="col-md-6"><p><strong style="color: red"><?php echo e(isset($total_money_vnd_finally) && is_numeric($total_money_vnd_finally) ? number_format($total_money_vnd_finally, 0, ',', '.') : '0'); ?> VNĐ</strong></p></div>

                        <div class="col-md-6"><p>Số tiền cần thanh toán (EURO):</p></div>
                        <div class="col-md-6"><p><strong style="color: red"><?php echo e(isset($total_money_euro_finally) && is_numeric($total_money_euro_finally) ? number_format($total_money_euro_finally, 0, ',', '.') : '0'); ?> EURO</strong></p></div>

                        <div class="col-md-6"><p>Số tiền cần thanh toán (VNĐ) bằng chữ:</p> </div>
                        <div class="col-md-6"><p class="text-capitalize"><strong><?php echo e($total_money_vnd_finally_word ?? 0); ?> đồng</strong></p> </div>

                        <?php if($total_money_euro_finally>0): ?> 
                            <div class="col-md-6"><p>Số tiền cần thanh toán (Euro) bằng chữ:</p> </div>
                            <div class="col-md-6"><p class="text-capitalize"><strong><?php echo e($total_money_euro_finally_word ?? 0); ?> đồng</strong></p> </div>
                        <?php endif; ?>
                    <?php endif; ?>    
                    <div class="col-md-12 show-print" style="margin-top: 30px">
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            <?php echo app('translator')->get('Ban Kiểm Soát'); ?>
                        </div>
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            <?php echo app('translator')->get('Kế toán'); ?>
                        </div>
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            <?php echo app('translator')->get('Hành chính'); ?>
                        </div>
                        <div class="col-xs-3 text-center text-bold text-uppercase">
                            <?php echo app('translator')->get('Giám đốc CN'); ?>
                        </div>
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            <?php echo app('translator')->get('Người đề nghị'); ?>
                        </div>
                    </div>

                    <?php if($detail->status == 'paid'): ?>
                    <div class="col-md-12 show-print" style="margin-top: 100px">
                        <div class="col-xs-3 text-center text-bold ">
                            <?php echo e($detail->approved_admin->name??""); ?>  <?php echo app('translator')->get('Đã Duyệt'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="box-footer hide-print">
                <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                </a>
                <?php if($detail->status == 'new'): ?>
                    <button data-id="<?php echo e($detail->id); ?>" type="button"
                        class="approve_payment btn btn-info btn-sm pull-right">
                        <i class="fa fa-money"></i> <?php echo app('translator')->get('Duyệt'); ?>
                    </button>
                <?php else: ?>
                    <button type="button" class= "btn btn-danger btn-sm pull-right">
                        <i class="fa fa-money"></i> <?php echo e($detail->approved_admin->name??""); ?>  <?php echo app('translator')->get('Đã Duyệt'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.approve_payment').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn duyệt đề xuất này ?')) {
                let _id = $(this).attr('data-id');
                let url = "<?php echo e(route('payment.approve')); ?>/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/payment_request/show.blade.php ENDPATH**/ ?>