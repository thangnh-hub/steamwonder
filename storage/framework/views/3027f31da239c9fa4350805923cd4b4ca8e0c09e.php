<!-- jQuery 3 -->
<script src="<?php echo e(asset('themes/admin/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('themes/admin/js/jquery.validate.min.js')); ?>"></script>
<!-- CKEditor-->
<script src="<?php echo e(asset('vendor/ckeditor/ckeditor.js')); ?>"></script>

<!-- ckfinder-->

<?php echo $__env->make('ckfinder::setup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- Custom & config js -->
<script src="<?php echo e(asset('themes/admin/js/custom.js')); ?>"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?php echo e(asset('themes/admin/js/bootstrap.min.js')); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo e(asset('themes/admin/plugins/slimScroll/jquery.slimscroll.min.js')); ?>"></script>
<!-- Select2 -->
<script src="<?php echo e(asset('themes/admin/plugins/select2/select2.full.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('themes/admin/js/app.min.js')); ?>"></script>

<script src="<?php echo e(asset('themes/admin/plugins/nestable/jquery.nestable.min.js')); ?>"></script>

<script>
    $(".select2").select2();

    // Call single input
    function _lfm(th) {
        $(th).filemanager('Images', {
            prefix: route_prefix
            // prefix: '<?php echo e(route('ckfinder_browser')); ?>'
        });
    }

    $('.lfm').filemanager('Images', {
        prefix: '<?php echo e(route('ckfinder_browser')); ?>'
    });

    $('.file').filemanager('Files', {
        prefix: '<?php echo e(route('ckfinder_browser')); ?>'
    });

    const filterArray = (array, fields, value) => {
        fields = Array.isArray(fields) ? fields : [fields];
        return array.filter((item) => fields.some((field) => item[field] === value));
    };

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [day, month, year].join('/');
    }
    $('.completed').on('click', function() {
        return false
    })
    $('.view_more_notify').click(function(e) {
        e.stopPropagation()
        var page = Number($('#toggle_notify').data('id'));
        var itemHtml = '';
        $.ajax({
            url: "<?php echo e(route('get.notify')); ?>",
            data: {
                page: page + 1,
            },
            type: 'GET',
            success: function(response) {
                $('#toggle_notify').attr('data-id', page + 1);
                if (response.data.rows != '') {
                    if (response.data.rows.last_page <= page + 1) {
                        $('.view_more_notify').hide();
                    }
                    $.each(response.data.rows.data, function(index, item) {
                        // Process each item in the array
                        itemHtml +=
                            '<li class="item_notify ' + (response.data.user_notify.includes(
                                item
                                .id) ? '' : 'notify') + '" data-id="' + item.id + '">';
                        itemHtml += '<a href="javascript:void(0)" title="' + item.title +
                            '">';
                        itemHtml += '<i class="fa fa-newspaper-o text-red"></i>';
                        itemHtml += item.title;
                        itemHtml += '</a></li>';
                    });
                    $('.list_notify').append(itemHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    })

    function toggleNotify() {
        var itemHtml = '';
        var page = Number($('#toggle_notify').data('id'));
        $.ajax({
            url: "<?php echo e(route('get.notify')); ?>",
            type: 'GET',
            success: function(response) {
                $('#toggle_notify').attr('data-id', 1);
                if (response.data.rows != '') {
                    if (response.data.rows.last_page <= 1) {
                        $('.view_more_notify').hide();
                    } else {
                        $('.view_more_notify').show();
                    }
                    $.each(response.data.rows.data, function(index, item) {
                        // Process each item in the array
                        itemHtml +=
                            '<li class="item_notify ' + (response.data.user_notify.includes(item
                                .id) ? '' : 'notify') + '" data-id="' + item.id + '">';
                        itemHtml += '<a href="javascript:void(0)" title="' + item.title + '">';
                        itemHtml += '<i class="fa fa-newspaper-o text-red"></i>';
                        itemHtml += item.title;
                        itemHtml += '</a></li>';
                    });
                    $('.list_notify').html(itemHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    }
    $(document).on('click', '.item_notify', function() {
        var id = $(this).data('id');
        var _this = $(this);
        let url = "<?php echo e(route('active.notify')); ?>";
        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: id,
            },
            success: function(response) {
                $('.modal_notify').attr('data-id', id)
                $('.modal_notify').modal('show');
                var detail = response.data;
                $('.modal_notify').find('.title_notify').html(detail.title);
                // $('.modal_notify').find('.link_notify').attr('href', detail.link).html(detail.link);
                if (response.message == 'true') {
                    // $('.notify_read').html(Number($('.notify_read').html()) - 1);
                    _this.removeClass('notify');
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    })

    $('form').on('focus', 'input[type=number]', function(e) {
        $(this).on('wheel.disableScroll', function(e) {
            e.preventDefault()
        })
    })
    $('form').on('blur', 'input[type=number]', function(e) {
        $(this).off('wheel.disableScroll')
    })

    function formatCurrency(amount) {
        if (!amount || isNaN(amount)) return "";
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount).replace('₫', 'đ');
    }

    function show_loading_notification() {
        $('#loading-notification').css('display', 'flex');
    }

    function hide_loading_notification() {
        setTimeout(() => {
            $('#loading-notification').css('display', 'none');
        }, 1500);
    }
</script>
<?php /**PATH C:\laragon\www\dwn\resources\views/admin/panels/scripts.blade.php ENDPATH**/ ?>