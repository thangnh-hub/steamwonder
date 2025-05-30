

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .table-bordered>thead>tr>th {
            text-align: center;
            vertical-align: middle;
        }

        @media  print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        
        <div class="box box-default hide-print">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form id="form_filter_warehouse" action="<?php echo e(route('report_order_entry_deliver')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Từ khóa'); ?> </label>
                                <input type="keyword" class="form-control" name="keyword" placeholder="Tên tài sản"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kho'); ?></label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Loại'); ?></label>
                                <select name="warehouse_type" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $warehouse_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['warehouse_type']) && $params['warehouse_type'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Danh mục'); ?></label>
                                    <select name="warehouse_category_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $warehouse_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($category_product->category_parent == '' || $category_product->category_parent == null): ?>
                                            <option <?php echo e(isset($params['warehouse_category_id_before']) && $params['warehouse_category_id_before'] == $category_product->id ? 'selected' : ''); ?> value="<?php echo e($category_product->id); ?>">
                                                <?php echo e($category_product->name ?? ''); ?></option>
                                            <?php $__currentLoopData = $warehouse_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category_sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($category_sub->category_parent == $category_product->id): ?>
                                                    <option <?php echo e(isset($params['warehouse_category_id_before']) && $params['warehouse_category_id_before'] == $category_sub->id ? 'selected' : ''); ?> value="<?php echo e($category_sub->id); ?>">
                                                        - - - <?php echo e($category_sub->name ?? ''); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Từ ngày'); ?> </label>
                                <input required type="date" class="form-control" name="from_date"
                                    value="<?php echo e(isset($params['from_date']) ? $params['from_date'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Đến ngày'); ?> </label>
                                <input required type="date" class="form-control" name="to_date"
                                    value="<?php echo e(isset($params['to_date']) ? $params['to_date'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('report_order_entry_deliver')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        
        <?php if(isset($rows)): ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?php echo app('translator')->get($module_name); ?></h3>
                    <a href="javascript:void(0)" data-url="<?php echo e(route('export_report_warhouse_entry_deliver')); ?>" class="btn btn-sm btn-success pull-right ml-15 hide-print btn_export_report"><i class="fa fa-file-excel-o"></i>
                         Export Excel</a>
                    <button id="printButton" onclick="window.print()" class="btn btn-primary btn-sm pull-right "><i class="fa fa-print"></i> In thông tin PDF</button>
                </div>
                <div class="box-body">
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

                    <?php if(count($rows) == 0): ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php echo app('translator')->get('not_found'); ?>
                            
                        </div>
                        
                    <?php else: ?>
                        <table class="table table-hover table-bordered sticky">
                            <thead>
                                <tr>
                                    <th style="width:50px" rowspan="2"><?php echo app('translator')->get('STT'); ?>
                                    </th>
                                    <th rowspan="2"><?php echo app('translator')->get('Tên TS (A)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="<?php echo app('translator')->get('Tên tài sản'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button> 
                                    </th>
                                    <th rowspan="2"><?php echo app('translator')->get('Danh mục (B)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="<?php echo app('translator')->get('Danh mục sản phẩm'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2"><?php echo app('translator')->get('Loại (C)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="<?php echo app('translator')->get('Loại sản phẩm'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2"><?php echo app('translator')->get('ĐVT (D)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="<?php echo app('translator')->get('Đơn vị tính'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th colspan="3"><?php echo app('translator')->get('Nhập '); ?> 
                                        
                                    </th>
                                    <th colspan="3"><?php echo app('translator')->get('Xuất'); ?>
                                        
                                    </th>
                                    <th colspan="2"><?php echo app('translator')->get('Điều chuyển '); ?>
                                        
                                    </th>
                                    <th ><?php echo app('translator')->get('Thu hồi '); ?>
                                        
                                    </th>
                                    <th colspan="3"><?php echo app('translator')->get('Tồn kho '); ?>
                                       
                                    </th>
                                </tr>    
                                <tr>
                                    <th style="width:75px"><?php echo app('translator')->get('Số lượng (J)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Số lượng đã nhập kho'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('Đơn giá (K)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Đơn giá nhập kho'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('Thành tiền (L)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Thành tiền nhập kho'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px"><?php echo app('translator')->get('Số lượng (M)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Số lượng đã xuất kho'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button> 
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('Đơn giá (N)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Đơn giá xuất kho'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('Thành tiền (O)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Thành tiền xuất kho'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px"><?php echo app('translator')->get('SL giao (P)'); ?> 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('SL giao điều chuyển'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('SL nhận (Q)'); ?> 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('SL nhận điều chuyển'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px"><?php echo app('translator')->get('Số lượng thu hồi (R)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Số lượng đã thu hồi'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px"><?php echo app('translator')->get('Đầu kỳ (S)'); ?> 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('S = (J + Q) - (M + P) (kỳ trước)'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('Cuối kỳ (T)'); ?>
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('T = (S) + (J + Q) - (M + P)'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px"><?php echo app('translator')->get('Hiện tại (U)'); ?> 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Tồn kho thực tế hiện tại'); ?>">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($params['from_date']) && $params['from_date'] != '' && isset($params['to_date']) && $params['to_date'] != ''): ?>
                                    <?php
                                        $stt=1;
                                    ?>
                                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="valign-middle">
                                            <td class="text-center">
                                                <?php echo e($stt++); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->product->name ?? ''); ?> <?php echo e($row->product->id ?? ''); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->product->category_product->name ?? ''); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(__($row->product->warehouse_type ?? '')); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(__($row->product->unit ?? '')); ?>

                                            </td>
                                            <td class="text-right">
                                                <?php echo e($row->nhap_kho_quantity ?? ''); ?>

                                            </td>
                                            <td class="text-right">
                                                <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0); ?>

                                            </td>
                                            <td class="text-right">
                                                <?php echo e(isset($row->nhap_kho_subtotal_money) && is_numeric($row->nhap_kho_subtotal_money) ? number_format($row->nhap_kho_subtotal_money, 0, ',', '.') : 0); ?>

                                            </td>
                                            <td class="text-right">
                                                <?php echo e($row->xuat_kho_quantity ?? ''); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0); ?>

                                            </td>
                                            <td class="text-right">
                                                <?php echo e(isset($row->xuat_kho_subtotal_money) && is_numeric($row->xuat_kho_subtotal_money) ? number_format($row->xuat_kho_subtotal_money, 0, ',', '.') : 0); ?>

                                            </td>
                                        
                                            <td class="text-right">
                                                <?php echo e($row->dieu_chuyen_giao_quantity ?? ''); ?>

                                            </td>
                                            
                                            <td class="text-right">
                                                <?php echo e($row->dieu_chuyen_nhan_quantity ?? ''); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e($row->thu_hoi_quantity ?? ''); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e($row->ton_kho_truoc_ky_quantity); ?>

                                            </td>

                                            <td  class="text-right">
                                                <?php echo e($row->ton_kho_trong_ky_quantity); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e($row->ton_kho_quantity); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(isset($row2) ): ?>
                                    <?php $__currentLoopData = $rows2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($row->ton_kho_truoc_ky_quantity != 0): ?>
                                        <tr class="valign-middle">
                                            <td class="text-center">
                                                <?php echo e($stt++); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->product->name ?? ''); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->product->category_product->name ?? ''); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(__($row->product->warehouse_type ?? '')); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(__($row->product->unit ?? '')); ?>

                                            </td>
                                            <td class="text-right">
                                                 0
                                            </td>
                                            <td class="text-right">
                                                <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0); ?>

                                            </td>
                                            <td class="text-right">
                                                0 
                                            </td>
                                            <td class="text-right">
                                                 0
                                            </td>

                                            <td class="text-right">
                                                <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0); ?>

                                            </td>
                                            <td class="text-right">
                                              0  
                                            </td>
                                        
                                            <td class="text-right">
                                                <?php echo e($row->dieu_chuyen_giao_quantity ?? ''); ?>

                                            </td>
                                            
                                            <td class="text-right">
                                                <?php echo e($row->dieu_chuyen_nhan_quantity ?? ''); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e($row->thu_hoi_quantity ?? ''); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e($row->ton_kho_truoc_ky_quantity); ?>

                                            </td>

                                            <td  class="text-right">
                                                <?php echo e($row->ton_kho_trong_ky_quantity); ?>

                                            </td>

                                            <td class="text-right">
                                                <?php echo e($row->ton_kho_quantity); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?>    
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <tr class="valign-middle text-bold" style="font-size: 15px;">
                                        <td colspan="5" class="text-right">
                                        </td>
                                        <td colspan="2" class="text-right">
                                            <?php echo app('translator')->get('Tổng tiền nhập hàng:'); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo e(number_format($rows->sum('nhap_kho_subtotal_money'), 0, ',', '.')); ?>

                                        </td>
                                        <td colspan="2" class="text-right">
                                            <?php echo app('translator')->get('Tổng tiền xuất hàng:'); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo e(number_format($rows->sum('xuat_kho_subtotal_money'), 0, ',', '.')); ?>

                                        </td>
                                        <td colspan="5" class="text-right">
                                            <?php echo app('translator')->get('Tổng tiền hàng tồn:'); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo e(number_format($rows->map(fn($item) => (int)$item['ton_kho_quantity'] * (int)$item['price'])->sum(), 0, ',', '.')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>    
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $('.btn_export_report').click(function() {
            var formData = $('#form_filter_warehouse').serialize();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                data: formData,
                success: function(response) {
                    if (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'reporEntryDeliver.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });
        })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse/report_order_entry_deliver.blade.php ENDPATH**/ ?>