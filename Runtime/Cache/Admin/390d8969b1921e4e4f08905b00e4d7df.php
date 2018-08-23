<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>厦门房客行后台管理系统</title>
    <meta name="keywords"/>
    <meta name="description"/>
    <link rel="stylesheet" href="/public/Admin/css/style.min.css">
    <link rel="stylesheet" href="/public/Static/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/Static/assets/bootstrap-table/src/bootstrap-table.css">
    <link rel="stylesheet" href="/public/Static/assets/bootstrap-table/src/bootstrap-editable.css">
    <link rel="stylesheet" href="/public/Static/assets/examples.css">
    <link rel="stylesheet" href="/public/Admin/css/font-awesome.min.css">
    <link rel="stylesheet" href="/public/Admin/css/custom.css">
    <link rel="stylesheet" href="/public/Admin/css/animate.min.css">
    <link rel="stylesheet" href="/public/Admin/css/huge.css">
    <link rel="stylesheet" href="/public/Admin/css/toastr.min.css">
    <link rel="stylesheet" href="/public/Admin/css/sweetalert.css">
    <link rel="stylesheet" href="/public/Admin/css/summernote.css">
    <script type="text/javascript" src='/public/Static/jquery-1.11.2.min.js'></script>
    <script type="text/javascript" src="/public/Admin/js/jquery.js"></script>
    <script>
        // 现在window.$和window.jQuery是1.11版本:
        console.log($().jquery); // => '1.11.0'
        var $jq = jQuery.noConflict(true);
        // 现在window.$和window.jQuery被恢复成1.5版本:
        console.log($().jquery); // => '1.5.0'
        // 可以通过$jq访问1.11版本的jQuery了
    </script>


    <link rel="stylesheet" href="/public/Admin/css/jquery.autocomplete.css">
    <script type="text/javascript" src="/public/Admin/js/jquery.bgiframe.min.js"></script>
    <script type="text/javascript" src="/public/Admin/js/jquery.ajaxQueue.js"></script>
    <script type="text/javascript" src="/public/Admin/js/jquery.autocomplete.js"></script>
    <script src="/public/Static/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/public/Static/assets/bootstrap-table/src/bootstrap-table.js"></script>
    <script src="/public/Static/assets/bootstrap-table/src/extensions/export/bootstrap-table-export.js"></script>
    <script src="/public/Static/assets/tableExport.js"></script>
    <script src="/public/Static/assets/bootstrap-table/src/extensions/editable/bootstrap-table-editable.js"></script>
    <script src="/public/Static/assets/bootstrap-table/src/bootstrap-editable.js"></script>
    <script src="/public/Admin/js/icheck.min.js"></script>
    <script src="/public/Admin/js/content.min.js"></script>
    <script src="/public/Admin/js/bootstrapValidator.js"></script>
    <script src="/public/Admin/js/toastr.min.js"></script>
    <script src="/public/Static/assets/analytics.js"></script>
    <script src="/public/Admin/js/sweetalert.min.js"></script>
    <script src="/public/Admin/js/layer/laydate/laydate.js"></script>
    <script type="text/javascript" src="/public/Static/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="/public/Admin/js/summernote.min.js"></script>
    <script type="text/javascript" src="/public/Admin/js/summernote-zh-CN.js"></script>
    <script src="/public/Admin/js/huge.js"></script>
    
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- 主体 -->
        
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>出租房管理</h5>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div id="toolbar">
                        <button id="add" class="btn btn-success">新增</button>
                        <button id="remove" class="btn btn-danger" disabled>
                            <i class="glyphicon glyphicon-remove"></i> 删除
                        </button>
                    </div>
                    <table id="table"></table>
                </div>
            </div>
        </div>
    </div>

    <!-- /主体 -->
</div>
<!-- 底部 -->

<!-- /底部 -->

    <div class="modal fade" id="Modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>


    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?php echo ($BaiduMapAk); ?>"></script>
    <script type="text/javascript" src='/public/Static/ue/ueditor.config.js'></script>
    <script type="text/javascript" src='/public/Static/ue/ueditor.all.min.js'></script>

    <script type="text/javascript">
        var $table = $('#table'), selections = [];
        $table.bootstrapTable({
            url: "<?php echo U('RentalHouse/loadRentalHouse');?>",
            toolbar: "#toolbar",
            search: true,
            showRefresh: true,
            showColumns: true,
            showExport: true,
            minimumCountColumns: 5,
            pagination: true,
            idField: "id",
            sortName: "aid",
            //detailFormatter: "detailFormatter",
            clickToSelect : true,
            order: "desc",
            pageSize : 10,
            pageNumber : 1,
            pageList: [10,20,50,100,"ALL"],
            showFooter: false,
            sidePagination: "server",
            //responseHandler: "responseHandler",
            formatSearch: function(){return '请输入出租房名称'},
            height: getHeight(),
            formatLoadingMessage: function(){
                return '正在加载，请稍候...';
            },
            formatShowingRows: function (pageFrom, pageTo, totalRows) {
                return '第 ' + pageFrom + ' - ' + pageTo + ' 记录，共 ' + totalRows + ' 条记录';
            },
            formatRecordsPerPage: function(pageNumber){
                return '每页 '+pageNumber + ' 记录';
            },
            formatNoMatches: function()
            {
                return '没有数据！';
            },
            columns: [
                {checkbox: true, align: 'center', valign: 'middle'},
                {field: 'id',title: '房屋id', align: 'center',sortable: true},
                {field: 'name',title: '房屋名称', align: 'center'},
                {field: 'stateCaption', title: '房屋状态', align: 'center'},
                {field: 'typeCaption', title: '房屋类型', align: 'center'},
                {field: 'cityCaption', title: '城市', align: 'center'},
                {field: 'areaCaption', title: '区域', align: 'center'},
                {field: 'address', title: '地址', align: 'center'},
                {field: 'price', title: '单价', align: 'center', formatter: function(value, row,index)
                    {
                        return parseFloat(value).toFixed(2);
                    }},
                //{field: 'renovationStandard', title: '楼盘户型'0, align: 'center'},
                {field: 'commission', title: '佣金', align: 'center'},
                {field: 'sort', title:'精选顺序', align:'center'},
                {title:'操作', align:'center',clickToSelect: false, formatter: function (value, row, index)
                    {
                    return '<button name="edit" data-id="'+row.id+'" class="btn btn-primary">编辑</button>' +
                        '<button name="extra" data-id="'+row.id+'" class="btn btn-primary">扩展信息</button>'+
                        '<button name="setCard" data-id="'+row.id+'" class="btn btn-primary">设置卡券</button>';
                    }}
            ],
            onCheck: checkRemoveBtn,
            onUncheck: checkRemoveBtn,
            onCheckAll: checkRemoveBtn,
            onUncheckAll: checkRemoveBtn
        });
        function checkRemoveBtn()
        {
            $('#remove').prop('disabled', !$table.bootstrapTable('getSelections').length);
        }
        $('#remove').click(function ()
        {
            var selections = $table.bootstrapTable('getSelections');
            if(!selections.length)
            {
                return;
            }
            var ids = [];
            $(selections).each(function (index, item)
            {
                ids.push(item.id);
            });
            swal({
                title: "确定删除吗？",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定删除！",
                cancelButtonText: "取消",
                closeOnConfirm: false
            },
            function(){
                $.post("<?php echo U('/Admin/RentalHouse/delete');?>", {ids:ids}, function(result) {
                    if(result.status==1){
                        swal({
                            title:"操作成功！",
                            text:result.info,
                            type:"success"
                        },function(){
                            $table.bootstrapTable('refresh', {});
                        });
                    }else{
                        toastr.warning(result.info);return false;
                    }
                }, 'json');
            });
        });
        $(window).resize(function () {
            $table.bootstrapTable('resetView', {
                height: getHeight()
            });
        });
        $table.delegate('[name="edit"]','click', function ()
        {
            var id = $(this).attr('data-id');
            $("#Modal").modal({
                remote: "<?php echo U('/Admin/RentalHouse/edit');?>?id="+id,
                backdrop: 'static',
                keyboard: false
            });
        });
        $('#add').click(function(){
            $("#Modal").modal({
                remote: "<?php echo U('/Admin/RentalHouse/add');?>",
                backdrop: 'static',
                keyboard: false
            });
        });
        $("#Modal").on("hidden.bs.modal", function() {
            $table.bootstrapTable('refresh', {});
            if(document.getElementById('video_preview'))
            {
                document.getElementById('video_preview').currentTime = 0;
                document.getElementById('video_preview').pause();
            }
            $(this).removeData("bs.modal");
        });
        $table.delegate('[name="extra"]','click', function ()
        {
            var id = $(this).attr('data-id');
            $("#Modal").modal({
                remote: "<?php echo U('/Admin/RentalHouse/editExtra');?>?id="+id,
                backdrop: 'static',
                keyboard: false
            });
        });
        $table.delegate('[name="setCard"]','click', function ()
        {
            var id = $(this).attr('data-id');
            $("#Modal").modal({
                remote: "<?php echo U('/Admin/RentalHouse/setCard');?>?id="+id,
                backdrop: 'static',
                keyboard: false
            });
        });

    </script>

</body>
</html>