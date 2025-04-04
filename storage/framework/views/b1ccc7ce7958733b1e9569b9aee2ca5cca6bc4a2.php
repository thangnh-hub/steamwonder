<footer class="main-footer hide-print">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0.0
    </div>
    <strong>Copyright &copy; 2024 <a href="#" target="_blank">DWN SOFTWARE</a>.</strong> All rights
    reserved.
</footer>

<div id="loading">
    <div id="overlay" class="overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw "></i></div>
</div>

<div class="modal fade modal_notify" data-id ="" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ModalNotify"
    aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalNotify">
                        <?php echo app('translator')->get('Chi tiết thông báo'); ?>
                    </h4>
                </div>
                <div class="modal-body modal-body-add-leson">
                    <p class="title_notify"></p>
                    <a class="link_notify" href=""></a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo app('translator')->get('Đóng'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/panels/footer.blade.php ENDPATH**/ ?>