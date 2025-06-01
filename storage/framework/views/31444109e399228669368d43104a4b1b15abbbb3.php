

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
    <style>
        table {
            border: 1px solid #dddddd;
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        th,
        td {
            border: 1px solid #dddddd;
        }

        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-success pull-right" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                <?php echo app('translator')->get('List'); ?></a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
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

        <form role="form" class="form_inventory" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Create form'); ?></h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>
                        <?php echo csrf_field(); ?>
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Kỳ kiểm kê'); ?> <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="period"
                                            placeholder="<?php echo app('translator')->get('Kỳ kiểm kê'); ?>" value="<?php echo e(old('period')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người thực hiện'); ?><small class="text-red">*</small></label>
                                        <select required name="person_id" class=" form-control select2">
                                            <option value="">Chọn</option>
                                            <?php $__currentLoopData = $persons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"
                                                    <?php echo e(isset($detail->person_id) && $detail->person_id == $val->id ? 'selected' : ''); ?>>
                                                    <?php echo e($val->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ngày kiểm kê'); ?> <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="date_received"
                                            value="<?php echo e($detail->day_create ?? date('Y-m-d', time())); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Cơ sở'); ?><small class="text-red">*</small></label>
                                        <select name="area_id" class="area_id form-control select2" required>
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>">
                                                    <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Kho'); ?><small class="text-red">*</small></label>
                                        <select name="warehouse_id" class="warehouse_id form-control select2" required>
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                        <select class="form-control select2 department" name="department">
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                            <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Vị trí'); ?></label>
                                        <select class="form-control select2 positions" name="positions_id">
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ghi chú'); ?></label>
                                        <textarea name="json_params[note]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button"
                                            class="btn btn-success btn_get_view_product"><?php echo app('translator')->get('Lấy danh sách sản phẩm'); ?></button>
                                    </div>
                                </div>

                                <div class="col-md-12" style="border-top: 1px solid #ccc; padding-top:15px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="box-title" style="padding-bottom:10px;"><?php echo app('translator')->get('Danh sách sản phẩm'); ?></h4>
                                            <table id="myTable" class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                                        <th><?php echo app('translator')->get('Mã tài sản'); ?></th>
                                                        <th><?php echo app('translator')->get('Tên tài sản'); ?></th>
                                                        <th><?php echo app('translator')->get('Loại tài sản'); ?></th>
                                                        <th><?php echo app('translator')->get('Danh mục'); ?></th>
                                                        <th><?php echo app('translator')->get('Quy cách'); ?></th>
                                                        <th><?php echo app('translator')->get('Xuất xứ'); ?></th>
                                                        <th><?php echo app('translator')->get('Hãng SX'); ?></th>
                                                        <th><?php echo app('translator')->get('Bảo hành'); ?></th>
                                                        <th><?php echo app('translator')->get('Tình trạng'); ?></th>
                                                        <th style="width: 170px"><?php echo app('translator')->get('Phòng ban'); ?></th>
                                                        <th style="width: 170px"><?php echo app('translator')->get('Vị trí'); ?></th>
                                                        <th style="width: 150px"><?php echo app('translator')->get('Số lượng tồn kho'); ?></th>
                                                        <th style="min-width: 200px"><?php echo app('translator')->get('Ghi chú'); ?></th>
                                                        <th><?php echo app('translator')->get('Chọn'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-order" id="view_list_product">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-success btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <div class="pull-right">
                                <input type="hidden" class="synchronize" name="synchronize" value="">
                                <button type="button" style="margin-right: 30px" name="synchronize"
                                    class="synchronize btn btn-warning btn-sm"><i class="fa fa-floppy-o"></i>
                                    <?php echo app('translator')->get('Lưu và đồng bộ'); ?></button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i>
                                    <?php echo app('translator')->get('Lưu lại '); ?></button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        var warehouses = <?php echo json_encode($warehouses ?? [], 15, 512) ?>;
        var positions = <?php echo json_encode($positions ?? [], 15, 512) ?>;
        var departments = <?php echo json_encode($department ?? [], 15, 512) ?>;
        var state = <?php echo json_encode($state ?? [], 15, 512) ?>;
        $('.area_id').change(function() {
            var area_id = $(this).val();
            var _html = '<option value=""><?php echo app('translator')->get('Please select'); ?></option>';
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
            var _html = '<option value=""><?php echo app('translator')->get('Please select'); ?></option>';
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
            $('.positions').html(_html).trigger('change');
        })

        // Lưu và đồng bộ
        $('.synchronize').click(function() {
            if (!$('.form_inventory')[0].checkValidity()) {
                $('.form_inventory')[0].reportValidity();
                return false;
            }
            // Nếu dữ liệu hợp lệ, hiển thị xác nhận
            if (confirm('Thao tác này không thể hoàn lại.\nBạn chắc chắn muốn lưu và đồng bộ tài sản!')) {
                $('.synchronize').val('synchronize');
                $('.form_inventory').submit();
            }
        });

        $('.warehouse_id, .department, .positions').change(function() {
            $('#view_list_product').html('');
        })

        $('.btn_get_view_product').click(function() {
            var area_id = $('.area_id').val();
            var warehouse_id = $('.warehouse_id').val();
            var department_id = $('.department').val();
            var positions_id = $('.positions').val();
            if (area_id == '' || warehouse_id == '') {
                alert('Cần chọn cơ sở và kho trước khi lấy sản phẩm !')
                return;
            }
            get_view_product(warehouse_id, department_id, positions_id);
        })

        function get_view_product(warehouse_id, department_id, position_id) {
            let url = "<?php echo e(route('warehouse_inventory.get_view_list_product')); ?>";
            let _targetHTML = $('#view_list_product');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    warehouse_id: warehouse_id,
                    department_id: department_id,
                    position_id: position_id,
                },
                success: function(response) {
                    $('#loading-notification').css('display', 'none');
                    var warehouse_asset = response.data.warehouse_asset
                    var position = response.data.positions
                    var _html = renderView(warehouse_asset,position);
                    _targetHTML.html(_html);
                    $('.select2').select2();

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    $('#loading-notification').css('display', 'none');
                    alert(errors);
                }
            });
        }

        function renderView(warehouse_asset,position) {
            var _html = '';
            var stt = 0;
            warehouse_asset.forEach(items => {
                stt++;
                _html += `
                        <tr class="text-center">
                            <td>
                                ` + stt + `
                            </td>
                            <td>
                                ${items.code ?? ''}
                            </td>
                            <td>
                                ${items.name ?? ''}
                            </td>
                            <td>
                                ${items.product_type ?? ''}
                            </td>
                            <td>
                                 ${items.product.category_product.name ?? ''}
                            </td>
                            <td>
                                ${items.product.json_params.specification ?? ''}
                            </td>
                            <td>
                               ${items.product.json_params.origin ?? ''}
                            </td>
                            <td>
                                ${items.product.json_params.manufacturer ?? ''}
                            </td>
                            <td>
                                ${items.product.json_params.warranty ?? ''}
                            </td>
                            <td>
                                <select class="form-control select2" name="asset[${items.id ?? ''}][state]" style="width: 100%"
                                    ${items.product_type == 'vattutieuhao' ? 'disabled' : ''}>
                                    <option value=""><?php echo app('translator')->get('Trình trạng'); ?></option>`;
                                    Object.entries(state).forEach(([key, val]) => {
                                        _html += `
                                        <option value="${key ?? ''}"
                                        ${items.state == key ? 'selected' : ''}>
                                        ${val ?? ''} </option>
                                        `;
                                    });
                                _html += ` </select>
                            </td>
                            <td>
                                <select class="form-control select2" name="asset[${items.id ?? ''}][department_id]" style="width: 100%">
                                    <option value=""><?php echo app('translator')->get('Phòng ban'); ?></option>`;

                                    Object.entries(departments).forEach(([key, val]) => {
                                        _html += `
                                        <option value="${val.id}"
                                        ${items.department_id == val.id ? 'selected' : ''}>
                                        ${val.name ?? ''} </option>
                                        `;
                                    });
                                    _html += `</select>
                            </td>
                            <td>
                                <select class="form-control select2" name="asset[${items.id ?? ''}][position_id]" style="width: 100%">
                                    <option value=""><?php echo app('translator')->get('Vị trí'); ?></option>`;
                                    Object.entries(position).forEach(([key, val_p])  => {
                                        if(val_p.parent_id =='' || val_p.parent_id == null){
                                            console.log(val_p.parent_id);
                                            _html += `
                                                <option value="${val_p.id}"
                                                ${items.position_id == val_p.id ? 'selected' : ''}>
                                                ${val_p.name ?? ''} </option>
                                                `;
                                            Object.entries(position).forEach(([key1, val_p1])  => {
                                                if(val_p1.parent_id == val_p.id){
                                                    _html += `
                                                        <option value="${val_p1.id}"
                                                        ${items.position_id == val_p1.id ? 'selected' : ''}>
                                                        - - ${val_p1.name ?? ''} </option>
                                                        `;
                                                    Object.entries(position).forEach(([key2, val_p2])  => {
                                                        if(val_p2.parent_id == val_p1.id){
                                                            _html += `
                                                                <option value="${val_p2.id}"
                                                                ${items.position_id == val_p2.id ? 'selected' : ''}>
                                                                - - - - ${val_p2.name ?? ''} </option>
                                                                `;
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                _html += `</select>
                            </td>
                            <td>
                                <input ${items.product_type =='vattutieuhao'? '' : 'readonly'} type="number"
                                name="asset[${items.id ?? ''}][quantity]" class="form-control" value="${items.quantity ?? 0}"
                                min="0">
                            </td>
                            <td>
                                <textarea cols="3" name="asset[${items.id ?? ''}][note]" class="form-control"></textarea>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="$(this).parents('tr').remove();" data-toggle="tooltip"
                                    title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        `;
            });
            return _html;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_inventory/create.blade.php ENDPATH**/ ?>