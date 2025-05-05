

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
  <style>
        th{
            text-align: center;
            vertical-align: middle;
        }
        .modal-header{
            background-color: #3c8dbc;
            color: white;
        }
        .table-wrapper {
            max-height: 450px; 
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }

        .table-wrapper table {
            border-collapse: separate;
            width: 100%;
        }
  </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
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

        
        <div class="box box-default">
            <div class="box-body ">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="">
                                            <a href="#tab_1" data-toggle="tab">
                                                <h5>Thông tin chính </h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_2" data-toggle="tab">
                                                <h5>Người thân của bé</h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_3" data-toggle="tab">
                                                <h5>Dịch vụ đăng ký</h5>
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="#tab_4" data-toggle="tab">
                                                <h5>Biên lai thu phí</h5>
                                            </a>
                                        </li>
                                    </ul>
    
                                    <div class="tab-content">
                                        <!-- TAB 1: Thông tin học sinh -->
                                        <div class="tab-pane" id="tab_1">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong><?php echo app('translator')->get('Mã học sinh'); ?>:</strong>
                                                        <?php echo e($detail->student_code  ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Họ và tên'); ?>:</strong>
                                                        <?php echo e($detail->last_name ?? ''); ?> <?php echo e($detail->first_name ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Ngày sinh'); ?>:</strong>
                                                        <?php echo e($detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Tên thường gọi'); ?>:</strong>
                                                        <?php echo e($detail->nickname ?? ''); ?>

                                                    </p>
                                                </div>
                                    
                                                <div class="col-md-4">
                                                    <p><strong><?php echo app('translator')->get('Khu vực'); ?>:</strong>
                                                        <?php echo e($detail->area->name ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Giới tính'); ?>:</strong>
                                                        <?php echo e(__($detail->sex ?? '')); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Lớp đang học'); ?>:</strong>
                                                        <?php echo e($detail->currentClass->name ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Ngày nhập học'); ?>:</strong>
                                                        <?php echo e(isset($detail->enrolled_at) &&  $detail->enrolled_at !="" ?date("d-m-Y", strtotime($detail->enrolled_at)): ''); ?>

                                                    </p>
                                                </div>
                                    
                                                <div class="col-md-4">
                                                    <p><strong><?php echo app('translator')->get('Ảnh đại diện'); ?>:</strong>
                                                    </p>
                                                    <a target="_blank" href="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>">
                                                        <img src="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>" alt="avatar" style="max-height:180px;">
                                                    </a>   
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <!-- TAB 2: Người thân -->
                                        <div class="tab-pane" id="tab_2">
                                            <?php if($detail->studentParents->isNotEmpty()): ?>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Avatar'); ?></th>
                                                            <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                                            <th><?php echo app('translator')->get('Mối quan hệ'); ?></th>
                                                            <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày sinh'); ?></th>
                                                            <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                                            <th><?php echo app('translator')->get('Email'); ?></th>
                                                            <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $detail->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($index + 1); ?></td>
                                                                <td>
                                                                    <?php if(!empty($relation->parent->avatar)): ?>
                                                                        <img src="<?php echo e(asset($relation->parent->avatar)); ?>" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                    <?php else: ?>
                                                                        <span class="text-muted">No image</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?php echo e($relation->parent->last_name ?? ''); ?> <?php echo e($relation->parent->first_name ?? ''); ?></td>
                                                                <td><?php echo e($relation->relationship->title ?? ''); ?></td>
                                                                <td><?php echo e(__($relation->parent->sex ?? '')); ?></td>
                                                                
                                                                <td>
                                                                    <?php echo e($relation->parent->birthday ? \Carbon\Carbon::parse($relation->parent->birthday)->format('d/m/Y') : ''); ?>

                                                                </td>
                                                                
                                                                <td><?php echo e($relation->parent->phone ?? ''); ?></td>
                                                                <td><?php echo e($relation->parent->email ?? ''); ?></td>
                                                                <td><?php echo e($relation->parent->address ?? ''); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p class="text-muted"><?php echo app('translator')->get('Không có người thân nào được liên kết.'); ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <!-- TAB 3: Dịch vụ đăng ký -->
                                        <div class="tab-pane" id="tab_3">
                                            <div class="box-body ">
                                                <h4 class="mt-4 ">Danh sách dịch vụ đăng ký</h4>
                                                <br>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Nhóm dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Hệ đào tạo'); ?></th>
                                                            <th><?php echo app('translator')->get('Độ tuổi'); ?></th>
                                                            <th><?php echo app('translator')->get('Tính chất dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Biểu phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Chu kỳ thu'); ?></th>
                                                            <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $activeServices = $detail->studentServices->where('status', 'active');
                                                        ?>
                                                        <?php if($activeServices->count()): ?>
                                                        <?php $__currentLoopData = $activeServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($loop->index + 1); ?></td>
                                                            <td><?php echo e($row->services->name ?? ""); ?></td>
                                                            <td><?php echo e($row->services->service_category->name ?? ""); ?></td>
                                                            <td><?php echo e($row->services->education_program->name ?? ""); ?></td>
                                                            <td><?php echo e($row->services->education_age->name ?? ""); ?></td>
                                                            <td><?php echo e($row->services->is_attendance== 0 ? "Không theo điểm danh" : "Tính theo điểm danh"); ?></td>
                                                            <td><?php echo e(__($row->services->service_type??"")); ?></td>
                                                            
                                                            <td>
                                                                <?php if(isset($row->services->serviceDetail) && $row->services->serviceDetail->count() > 0): ?>
                                                                <?php $__currentLoopData = $row->services->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <ul>
                                                                    <li>Số tiền: <?php echo e(isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : ''); ?></li>
                                                                    <li>Số lượng: <?php echo e($detail_service->quantity ?? ''); ?></li>
                                                                    <li>Từ: <?php echo e((isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '')); ?></li>
                                                                    <li>Đến: <?php echo e((isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '')); ?></li>
                                                                </ul>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo e($row->paymentcycle->name ?? ""); ?>

                                                            </td>
                                                            <td>
                                                                <?php echo e($row->json_params->note ?? ""); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                    
                                                </table>
                                                <br>
                                                <?php
                                                    $cancelledServices = $detail->studentServices->where('status', 'cancelled');
                                                ?>
                                                <?php if($cancelledServices->count()): ?>
                                                <h4 class="mt-4 ">Danh sách dịch vụ bị huỷ</h4>
                                                <br>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày bắt đầu'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày kết thúc'); ?></th>
                                                            <th><?php echo app('translator')->get('Người cập nhật'); ?></th>
                                                            <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $cancelledServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->index + 1); ?></td>
                                                                <td><?php echo e($row->services->name ?? ''); ?></td>
                                                                <td>
                                                                    <?php echo e(optional($row->services->serviceDetail->first())->start_at 
                                                                        ? \Carbon\Carbon::parse($row->services->serviceDetail->first()->start_at)->format('d-m-Y') 
                                                                        : ''); ?>

                                                                </td>
                                                                <td>
                                                                    <?php echo e(optional($row->services->serviceDetail->first())->end_at 
                                                                        ? \Carbon\Carbon::parse($row->services->serviceDetail->first()->end_at)->format('d-m-Y') 
                                                                        : ''); ?>

                                                                </td>
                                                                <td>
                                                                    <?php echo e($row->adminUpdated->name ?? ""); ?> (<?php echo e($row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('H:i:s d-m-Y') : ''); ?>)   
                                                                </td>
                                                                <td><span class="badge badge-danger">Đã huỷ</span></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                                <?php endif; ?>
                                            </div>                      
                                        </div>

                                        <!-- TAB 4: Biên lai thu phí -->
                                        <div class="tab-pane active" id="tab_4">
                                            <div class="box-body ">
                                                <form method="POST" action="<?php echo e(route('receipt.calculStudent')); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="student_id" value="<?php echo e($detail->id); ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-money"></i> <?php echo app('translator')->get('Tính toán thu phí'); ?>
                                                    </button>
                                                </form>
                                                <br>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Mã biểu phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Tên biểu phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Chu kỳ'); ?></th>
                                                            <th><?php echo app('translator')->get('Biểu phí trước'); ?></th>
                                                            <th><?php echo app('translator')->get('Dư nợ trước'); ?></th>
                                                            <th><?php echo app('translator')->get('Thành tiền'); ?></th>
                                                            <th><?php echo app('translator')->get('Tổng giảm trừ'); ?></th>
                                                            <th><?php echo app('translator')->get('Tổng tiền truy thu/hoàn trả'); ?></th>
                                                            <th><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                                            <th><?php echo app('translator')->get('Đã thanh toán'); ?></th>
                                                            <th><?php echo app('translator')->get('Còn lại'); ?></th>
                                                            <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                            <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                                            <th><?php echo app('translator')->get('Người lập biên lai'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày lập biên lai'); ?></th>
                                                            <th><?php echo app('translator')->get('Chức năng'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            function format_currency($price) {
                                                                return (isset($price) && is_numeric($price)) 
                                                                    ? number_format($price, 0, ',', '.') . ' đ'
                                                                    : '';
                                                            }
                                                        ?>
                                                        <?php if($detail->studentReceipt->count()): ?>
                                                            <?php $__currentLoopData = $detail->studentReceipt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                            <tr>
                                                                <td><?php echo e($loop->index + 1); ?></td>  
                                                                <td><?php echo e($row->receipt_code ?? ""); ?></td>
                                                                <td><?php echo e($row->receipt_name ?? ""); ?></td>
                                                                <td><?php echo e($row->payment_cycle->name ?? ""); ?></td>
                                                                <td><?php echo e($row->prev_receipt->receipt_name  ?? ""); ?></td>
                                                                <td><?php echo e(format_currency($row->prev_balance)); ?></td>
                                                                <td><?php echo e(format_currency($row->total_amount)); ?></td>
                                                                <td><?php echo e(format_currency($row->total_discount)); ?></td>
                                                                <td><?php echo e(format_currency($row->total_adjustment)); ?></td>
                                                                <td><?php echo e(format_currency($row->total_final)); ?></td>
                                                                <td><?php echo e(format_currency($row->total_paid)); ?></td>
                                                                <td><?php echo e(format_currency($row->total_due)); ?></td>
                                                                <td><?php echo e(__($row->status)); ?></td>
                                                                <td><?php echo e($row->note ?? ""); ?></td>
                                                                <td><?php echo e($row->cashier->name ?? ""); ?></td>
                                                                <td><?php echo e((isset($row->receipt_date) ? \Illuminate\Support\Carbon::parse($row->receipt_date)->format('d-m-Y') : '')); ?> </td>
                                                                <td>
                                                                    
                                                                    <button type="button" data-id="<?php echo e($row->id); ?>" class="btn btn-primary btn-sm show_detail_receipt" data-toggle="modal" data-target="#showDetailReceipt">
                                                                        <i class="fa fa-money"></i> <?php echo app('translator')->get('Chi tiết'); ?>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>       
                                                    </tbody>
                                                </table>
                                            </div>                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer ">
                <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                </a>
            </div>
        </div>
    </section>

    <div class="modal fade" id="showDetailReceipt" tabindex="-1" role="dialog" aria-labelledby="showDetailReceipt" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showDetailReceipt"><?php echo app('translator')->get('Chi tiết biểu phí'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="table-wrapper" >
                        <table class="table table-hover table-bordered" >
                            <thead>
                                <tr>
                                    <th rowspan="2"><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Tháng áp dụng'); ?></th>
                                    <th colspan="2"><?php echo app('translator')->get('Số lượng sử dụng'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Giá'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Giảm trừ'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Thành tiền'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Truy thu/Hoàn trả'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Trạng thái'); ?></th>
                                    <th rowspan="2"><?php echo app('translator')->get('Cập nhật'); ?></th>
                                </tr>
                                <tr>
                                    <th><?php echo app('translator')->get('Dự kiến'); ?></th>
                                    <th><?php echo app('translator')->get('Thực tế'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="showDetailReceiptBody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
  <script>
    $('.show_detail_receipt').click(function(e) {
            e.preventDefault();
            let _id = $(this).data('id');
            let url = "<?php echo e(route('get_detail_receipt_info')); ?>";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                },
                success: function(response) {
                    console.log(response);
                    if (response.message == "success" && response.data.length > 0) {
                        let data = response.data;
                        let html = '';

                        $.each(data, function(index, item) {
                            html += '<tr>';
                            html += '<td>' + item.services_receipt.name + '</td>';
                            html += '<td>' + item.month + '</td>';
                            html += '<td>' + item.by_number + '</td>';
                            html += '<td>' + item.spent_number + '</td>';
                            html += '<td>' + item.unit_price + '</td>';
                            html += '<td>' + item.discount_amount + '</td>';
                            html += '<td>' + item.amount + '</td>';
                            html += '<td>' + item.adjustment_amount + '</td>';
                            html += '<td>' + item.final_amount + '</td>';
                            html += '<td>' + item.status + '</td>';
                            html += '<td>' + item.created_at + '</td>';
                            html += '</tr>';
                        });

                        $('.showDetailReceiptBody').html(html);
                    } else  {
                        $('.showDetailReceiptBody').html('<tr><td colspan="12" class="text-center">Không có dữ liệu</td></tr>');
                    } 
                    // Show the modal if the response is successful
                    if (response.message == "success") {
                        $('#showDetailReceipt').modal('show');
                    }
                },
                error: function(response) {
                    alert("Đã có lỗi xảy ra khi tải dữ liệu.");
                }
            });
        });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/students/detail.blade.php ENDPATH**/ ?>