

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .box_input_time{
            display: flex;
            gap: 5px;
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
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            
        </h1>
    </section>
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
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Mã tài sản, tên tài sản ...'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Mã tài sản, tên tài sản ...'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                <select name="department_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['department_id']) && $params['department_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tình trạng'); ?></label>
                                <select name="state" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['state']) && $params['state'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Loại tài sản'); ?></label>
                                <select name="product_type" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['product_type']) && $params['product_type'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Cơ sở'); ?></label>
                                <select name="area_id" class="area_id form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->code . '-' . $val->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kho'); ?></label>
                                <select name="warehouse_id" class="warehouse_id form-control select2">
                                    <option value="">Chọn</option>
                                    <?php if(isset($params['warehouse_id']) && $params['warehouse_id'] != ''): ?>
                                        <?php $__currentLoopData = $list_warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($params['area_id']) && $params['area_id'] != ''): ?>
                                                <?php if($val->area_id == $params['area_id']): ?>
                                                    <option value="<?php echo e($val->id); ?>"
                                                        <?php echo e(isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : ''); ?>>
                                                        <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Vị trí'); ?></label>
                                <select name="position_id" class="position_id form-control select2">
                                    <option value="">Chọn</option>
                                    <?php if(isset($params['position_id']) && $params['position_id'] != ''): ?>
                                        <?php if(isset($params['warehouse_id']) && $params['warehouse_id'] != ''): ?>
                                            <?php $__currentLoopData = $list_position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($val->warehouse_id == $params['warehouse_id']): ?>
                                                    <?php if(empty($val->parent_id)): ?>
                                                    <option value="<?php echo e($val->id); ?>"
                                                        <?php echo e(isset($params['position_id']) && $params['position_id'] == $val->id ? 'selected' : ''); ?>>
                                                        <?php echo app('translator')->get($val->name); ?></option>
                                                    
                                                    <?php $__currentLoopData = $list_position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($val1->parent_id == $val->id): ?>
                                                            <option value="<?php echo e($val1->id); ?>"
                                                                <?php echo e(isset($params['position_id']) && $params['position_id'] == $val1->id ? 'selected' : ''); ?>>
                                                                - - <?php echo app('translator')->get($val1->name); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phiếu nhập'); ?></label>
                                <select name="entry_id" class="entry_id form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $warehouse_entry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['entry_id']) && $params['entry_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->code); ?> - <?php echo e($val->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phiếu xuất'); ?></label>
                                <select name="deliver_id" class="deliver_id form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $warehouse_deliver; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['deliver_id']) && $params['deliver_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->code); ?> - <?php echo e($val->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>
            </div>
            <div class="box-body table-responsive box-alert">
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Mã tài sản'); ?></th>
                                <th><?php echo app('translator')->get('Tên tài sản'); ?></th>
                                <th><?php echo app('translator')->get('Loại tài sản'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Phiếu nhập'); ?></th>
                                <th><?php echo app('translator')->get('Phiếu xuất'); ?></th>
                                <th style="width: 200px"><?php echo app('translator')->get('Phòng ban'); ?></th>
                                <th><?php echo app('translator')->get('Người sử dụng'); ?></th>
                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                <th><?php echo app('translator')->get('Số lượng'); ?></th>
                                <th style="width: 200px"><?php echo app('translator')->get('Vị trí'); ?></th>
                                <th style="width: 200px"><?php echo app('translator')->get('Tình trạng'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                <th class="hide-print"><?php echo app('translator')->get('Chức năng'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($loop->index + 1); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->product->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo app('translator')->get($row->product_type ?? ''); ?>
                                    </td>

                                    <td>
                                        <?php echo e(__($row->status ?? '')); ?>

                                    </td>
                                    <td>
                                        <?php echo e(($row->warehouse_entry->code ?? '') . ' - ' . ($row->warehouse_entry->name ?? '')); ?>

                                    </td>
                                    <td>
                                        <?php echo e(($row->warehouse_deliver->code ?? '') . ' - ' . ($row->warehouse_deliver->name ?? '')); ?>

                                    </td>
                                    <td>
                                        <div class="box_view view_department">
                                            <?php echo e(__($row->department->name ?? '')); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <select class="form-control select2 department_id" style="width: 100%">
                                                <option value=""><?php echo app('translator')->get('Phòng ban'); ?></option>
                                                <?php $__currentLoopData = $list_department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($val->id); ?>"
                                                        <?php echo e(isset($row->department_id) && $row->department_id == $val->id ? 'selected' : ''); ?>>
                                                        <?php echo e($val->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo e(__($row->staff_entry_use->name ?? '')); ?>

                                    </td>
                                    <td>
                                        <?php echo e(__($row->warehouse->name ?? '')); ?>

                                    </td>
                                    <td>
                                        <?php echo e(__($row->quantity ?? '')); ?>

                                    </td>
                                    <td>
                                        <div class="box_view view_position">
                                            <?php echo e(__($row->position->name ?? '')); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <select class="form-control select2 position_id" style="width: 100%">
                                                <option value=""><?php echo app('translator')->get('Vị trí'); ?></option>
                                                <?php $__currentLoopData = $list_position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(empty($val->parent_id) && $val->warehouse_id == $row->warehouse_id): ?>
                                                        <option value="<?php echo e($val->id); ?>"
                                                            <?php echo e(isset($row->position_id) && $row->position_id == $val->id ? 'selected' : ''); ?>>
                                                            <?php echo app('translator')->get($val->name); ?></option>
                                                        <?php $__currentLoopData = $list_position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($val1->parent_id == $val->id): ?>
                                                                <option value="<?php echo e($val1->id); ?>"
                                                                    <?php echo e(isset($row->position_id) && $row->position_id == $val1->id ? 'selected' : ''); ?>>
                                                                    - - <?php echo app('translator')->get($val1->name); ?></option>
                                                                <?php $__currentLoopData = $list_position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if($val2->parent_id == $val1->id): ?>
                                                                        <option value="<?php echo e($val2->id); ?>"
                                                                            <?php echo e(isset($row->position_id) && $row->position_id == $val2->id ? 'selected' : ''); ?>>
                                                                            - - - - <?php echo app('translator')->get($val2->name); ?></option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view view_state">
                                            <?php echo e(__($row->state ?? '')); ?>

                                        </div>
                                        <?php if($row->product_type != 'vattutieuhao'): ?>
                                            <div class="box_edit" style="display: none">
                                                <select class="form-control select2 state" style="width: 100%">
                                                    <option value=""><?php echo app('translator')->get('Trình trạng'); ?></option>
                                                    <?php $__currentLoopData = $state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"
                                                            <?php echo e(isset($row->state) && $row->state == $key ? 'selected' : ''); ?>>
                                                            <?php echo app('translator')->get($val); ?> </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="box_view view_note">
                                            <?php echo e($row->json_params->note ?? ''); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <textarea class="form-control note" rows="3"><?php echo e($row->json_params->note ?? ''); ?></textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view">
                                            <button class="btn btn-sm btn-warning btn_edit" data-toggle="tooltip"
                                                style="margin-right: 5px" title="<?php echo app('translator')->get('Edit'); ?>"
                                                data-original-title="<?php echo app('translator')->get('Edit'); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <div class="box_input_time">
                                                <button class="btn btn-sm btn-success btn_save" data-toggle="tooltip"
                                                    data-id="<?php echo e($row->id); ?>"
                                                    data-original-title="<?php echo app('translator')->get('Lưu'); ?>"><i class="fa fa-check"
                                                        aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-danger btn_exit" data-toggle="tooltip"
                                                    data-original-title="<?php echo app('translator')->get('Hủy'); ?>"><i class="fa fa-times"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="box-footer clearfix hide-print">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy <?php echo e($rows->total()); ?> kết quả
                    </div>
                    <div class="col-sm-7">
                        <?php echo e($rows->withQueryString()->links('admin.pagination.default')); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        // var list_area = <?php echo json_encode($areas); ?>;
        var warehouses = <?php echo json_encode($list_warehouse); ?>;
        var positions = <?php echo json_encode($list_position); ?>;

        $('.area_id').on('change', function() {
            var area_id = $(this).val();
            var _html = '<option value="">Chọn</option>';
            if (area_id != '') {
                warehouses.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });
            }
            $('.warehouse_id').html(_html).trigger('change');
        })
        $('.warehouse_id').on('change', function() {
            var warehouse_id = $(this).val();
            var _html = '<option value="">Chọn</option>';
            if (warehouse_id != '') {
                positions.forEach(function(item) {
                    if (warehouse_id == item.warehouse_id) {
                        if (item.parent_id == null || item.parent_id == '') {
                            _html += `<option value = "` + item.id + `" > ` + item.name;
                            positions.forEach(function(sub) {
                                if (sub.parent_id == item.id) {
                                    _html += `<option value = "` + sub.id + `" > - - ` + sub.name;
                                    positions.forEach(function(sub_child) {
                                        if (sub_child.parent_id == sub.id) {
                                            _html += `<option value = "` + sub_child.id +
                                                `" > - - - - ` + sub_child.name;
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
            $('.position_id').html(_html).trigger('change');
        })


        $('.btn_edit').click(function() {
            var h = $(this).parents('tr').find('.box_view');
            var s = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        })
        $('.btn_exit').click(function() {
            var s = $(this).parents('tr').find('.box_view');
            var h = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        });
        $('.btn_save').click(function() {
            if (confirm('Bạn chắc chắn muốn lưu tài sản !')) {
                var _id = $(this).data('id');
                var url = "<?php echo e(route('warehouse_asset.update', ':id')); ?>".replace(':id', _id);
                // Lấy dữ liệu truyền ajax
                var state = $(this).parents('tr').find('.state').val();
                var department_id = $(this).parents('tr').find('.department_id').val();
                var position_id = $(this).parents('tr').find('.position_id').val();
                var note = $(this).parents('tr').find('.note').val();
                // var quantity = $(this).parents('tr').find('.quantity').val();
                // View đổi nội dung
                var view_state = $(this).parents('tr').find('.view_state');
                var view_department = $(this).parents('tr').find('.view_department');
                var view_position = $(this).parents('tr').find('.view_position');
                var view_note = $(this).parents('tr').find('.view_note');
                // var view_quantity = $(this).parents('tr').find('.view_quantity');
                // ẩn hiện
                var btn_exit = $(this).parents('tr').find('.btn_exit');
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        state: state,
                        department_id: department_id,
                        position_id: position_id,
                        note: note,
                        // quantity: quantity,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'warning') {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            ` + response.message + `
                                        </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);

                            } else {
                                var _html = `<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    ` + response.message + `
                                </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);
                                // Cập nhật lại view
                                view_state.html(response.data.state);
                                view_department.html(response.data.department);
                                view_position.html(response.data.position);
                                view_note.html(response.data.note);
                                // view_quantity.html(response.data.quantity);
                            }

                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            Bạn không có quyền thao tác chức năng này!
                                        </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                        btn_exit.click();
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }

        })
        function show_hide(show, hide) {
            show.show();
            hide.hide();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/warehouse_asset/index.blade.php ENDPATH**/ ?>