<div class="row ">
    <div id="vocabulary">
        <div class="vocabulary-item gra_voca_quiz-item">
            <div class="box box-primary">
                <div class=" box-header with-border">
                    <div class="col-lg-12 pl-0">
                        <h3 class="box-title">Từ vựng</h3>
                    </div>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <div class="tab_offline">
                            <div class="tab-pane active">
                                <div class="col-md-2 pl-0 mt-3 item_child">
                                    <div class="form-group" style="display: flex">
                                        <input class="form-control" data-toggle="tooltip" title="" type="text" name="json_params[vocabulary][]"
                                            placeholder="Nhập từ vựng..." value="">
                                        <button type="button" onclick="delete_vocabulary(this)"
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash "></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <button type="button" data-num="0" onclick="add_vocabulary(this)" class="btn btn-primary">
            Thêm từ vựng
        </button>
    </div>
</div>
<script>
    function delete_vocabulary(th) {
        $(th).parents('.item_child').remove();
    }
    function add_vocabulary(th) {
        var currentTime = $.now();
        var _html = `<div class="col-md-2 pl-0 mt-3 item_child">
                        <div class="form-group" style="display: flex">
                            <input class="form-control" type="text" name="json_params[vocabulary][]"
                                placeholder="Nhập từ vựng..." value="">
                            <button type="button" onclick="delete_vocabulary(this)"
                                class="btn btn-sm btn-danger"><i class="fa fa-trash "></i></button>
                        </div>
                    </div>`;
        $('#vocabulary .tab-pane').append(_html);
        $('.lfm').filemanager('image', {
            prefix: route_prefix
        });
    }

</script>

