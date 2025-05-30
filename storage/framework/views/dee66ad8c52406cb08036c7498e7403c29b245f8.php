

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>

    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST"
            onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Thông tin thu hồi tài sản'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Tên phiếu thu hồi'); ?> <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="<?php echo app('translator')->get('Tên phiếu thu hồi'); ?>" value="<?php echo e(old('name')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Kỳ'); ?> <small class="text-red">*</small></label>
                                        <input required type="month" class="form-control" name="period"
                                            value="<?php echo e(date('Y-m', time())); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người đề xuất'); ?><small class="text-red">*</small></label>
                                        <input type="text" class="form-control"
                                            value="<?php echo e($admin_auth->name . ' (' . $admin_auth->admin_code . ')'); ?>" disabled>
                                        <input type="hidden" class="form-control" name="staff_request"
                                            value="<?php echo e($admin_auth->id); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ngày đề xuất'); ?> <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="day_create"
                                            value="<?php echo e($detail->day_create ?? date('Y-m-d', time())); ?>">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ghi chú'); ?></label>
                                        <textarea name="json_params[note]" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Chọn tài sản thu hồi'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Cơ sở thu hồi'); ?><small class="text-red">*</small></label>
                                        <select name="area_id" class="area_id form-control select2">
                                            <option value="">Chọn</option>
                                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Kho thu hồi'); ?><small class="text-red">*</small></label>
                                        <select required name="warehouse_id" class="warehouse_id form-control select2">
                                            <option value="">Chọn</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Vị trí'); ?></label>
                                        <select name="json_params[position_id]" class="form-control select2 position_id">
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                        <select name="json_params[department_id]" class="department_id form-control select2"
                                            style="width: 100%;">
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                            <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người sử dụng'); ?></label>
                                        <select class="form-control select2 staff_request staff_entry" style="width: 100%;">
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Tên, mã tài sản'); ?></label>
                                        <input type="text" class="form-control keyword"
                                            placeholder="<?php echo app('translator')->get('Tên, mã tài sản'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Lọc tài sản'); ?></label>
                                        <div>
                                            <button onclick="filter_asset()" type="button"
                                                class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4><?php echo app('translator')->get('Danh sách tài sản thu hồi'); ?></h4>
                                    </div>
                                    <table class="table table-hover table-bordered sticky">
                                        <thead>
                                            <tr>
                                                <th><?php echo app('translator')->get('STT'); ?></th>
                                                <th><?php echo app('translator')->get('Mã Tài Sản'); ?></th>
                                                <th><?php echo app('translator')->get('Tên tài sản'); ?></th>
                                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                                <th><?php echo app('translator')->get('Phòng ban'); ?></th>
                                                <th><?php echo app('translator')->get('Người sử dụng'); ?></th>
                                                <th style="width: 180px"><?php echo app('translator')->get('Vị trí'); ?></th>
                                                <th>
                                                    <input id="allCheckbox" class="all_checkbox cursor mr-15"
                                                        type="checkbox" autocomplete="off">
                                                    <span class=""><?php echo app('translator')->get('Chọn tất cả'); ?> </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
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
        var positions = <?php echo json_encode($position ?? [], 15, 512) ?>;
        var staff_request = <?php echo json_encode($staff_request ?? [], 15, 512) ?>;
        $('.area_id').change(function() {
            var area_id = $(this).val();
            var _html = _html_staff = '<option value=""><?php echo app('translator')->get('Please select'); ?></option>';
            if (area_id != '') {
                warehouses.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });
                staff_request.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html_staff += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });

            }
            $('.warehouse_id').html(_html).trigger('change');
            $('.staff_request').html(_html_staff).trigger('change');
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
            $('.position_id').html(_html).trigger('change');
        })

        function checked_all() {
            $('#allCheckbox').change(function() {
                $('.each_checkbox').prop('checked', $(this).prop('checked'));
            });

            $('.each_checkbox').change(function() {
                $('#allCheckbox').prop('checked', $('.each_checkbox:checked').length === $('.each_checkbox')
                    .length);
            });
        }
        //hiển thị tài sản theo sản phẩm
        function filter_asset() {
            var area_id = $('.area_id').val();
            var keyword = $('.keyword').val();
            var warehouse_id = $('.warehouse_id').val();
            var position_id = $('.position_id').val();
            var department_id = $('.department_id').val();
            var staff_entry = $('.staff_entry').val();
            let url = "<?php echo e(route('warehouse_filter_asset_recall')); ?>"; //lấy danh sách tài sản
            let _targetHTML = $('.tbody-order-asset');
            if (!warehouse_id > 0) {
                alert('Vui lòng chọn kho thu hồi');
                return false;
            }
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    area_id: area_id,
                    keyword: keyword,
                    warehouse_id: warehouse_id,
                    position_id: position_id,
                    department_id: department_id,
                    staff_entry: staff_entry,
                    status: "<?php echo e(App\Consts::WAREHOUSE_ASSET_STATUS['deliver']); ?>",
                },
                success: function(response) {
                    $('#loading-notification').css('display', 'none');
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '';
                        let index = 1;
                        if (list.length > 0) {
                            list.forEach(items => {

                                var position = items.position;
                                _item += `<tr class="valign-middle">
                                        <td>${index }</td>
                                        <td>
                                            ${items.code ?? ''}
                                        </td>
                                        <td>
                                            ${items.name ?? ''}
                                        </td>
                                        <td>
                                            ${items.warehouse?.name ?? ''}
                                        </td>

                                        <td>
                                            ${items.department?.name ?? ''}
                                        </td>
                                        <td>
                                            ${items.staff_entry_use?.name ?? ''}
                                        </td>
                                        <td>
                                            <select name="asset[${items.product_id}][${index}][position]" class="form-control select2">
                                            <option value=""><?php echo app('translator')->get('Please select'); ?></option>`;
                                positions.forEach(function(item) {
                                    if (warehouse_id == item.warehouse_id) {
                                        if (item.parent_id == null || item.parent_id == '') {
                                            _item +=
                                                `<option ${item.id== items.position_id?'selected':''} value = "${item.id}" > ` +
                                                item.name;
                                            positions.forEach(function(sub) {
                                                if (sub.parent_id == item.id) {
                                                    _item += `<option ${sub.id== items.position_id?'selected':''} value = "${sub.id}" > - - ` + sub.name;
                                                    positions.forEach(function(
                                                        sub_child) {
                                                        if (sub_child
                                                            .parent_id == sub.id
                                                        ) {
                                                            _item +=
                                                                `<option ${sub_child.id== items.position_id?'selected':''} value = "${sub_child.id}" > - - - - ` +
                                                                sub_child.name;
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    }
                                });
                                _item += `</select>
                                                </td>
                                                <td>
                                                    <input name="asset[${items.product_id}][${index }][id]" class="each_checkbox mr-15 cursor"
                                                        type="checkbox" value="` + items.id + `" autocomplete="off">
                                                </td>
                                            </tr>`;
                                index++;

                            });
                            _targetHTML.html(_item);
                            $(".select2").select2();
                            checked_all()
                        }

                    } else {
                        _targetHTML.html(
                            '<tr><td colspan="8"><strong>Không tìm thấy bản ghi</strong></td></tr>');
                    }
                    _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    $('#loading-notification').css('display', 'none');
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_recall/create.blade.php ENDPATH**/ ?>