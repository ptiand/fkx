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
            <h5>用户信息</h5>
            <div class="ibox-tools" style="display:none;">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#">选项1</a>
                    </li>
                    <li><a href="#">选项2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div id="toolbar">

                           <!-- <a class="btn btn-success" data-toggle="modal" data-target="#addCustomer" data-remote="/admin/Customer/addCustomer">
                                新增1
                            </a>-->
                            <button id="addform" class="btn btn-success">
                                新增
                            </button>



                            <button id="edit" class="btn btn-primary" disabled>
                                编辑
                            </button>


                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="glyphicon glyphicon-remove"></i> 删除
                            </button>
                        <button id="editPWD" class="btn btn-primary" disabled>
                            重置密码
                        </button>

                    </div>
                    <table id="table"
                           data-toolbar="#toolbar"
                           data-search="true"
                           data-show-refresh="true"
                           data-show-columns="true"
                           data-show-export="true"
                           data-minimum-count-columns="5"
                           data-pagination="true"
                           data-id-field="id"
                           data-sort-name="created_at"
                           data-detail-formatter="detailFormatter"
                           data-click-select = true
                           data-order="desc"
                           data-page-size = "10"
                           data-page-number = "1"
                           data-page-list="[10,20,50,100,ALL]"
                           data-show-footer="false"
                           data-side-pagination="server"
                           data-url="<?php echo U('Customer/loadCustomer');?>"
                           data-response-handler="responseHandler">
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- /主体 -->
</div>
<!-- 底部 -->

<!-- /底部 -->

    <div class="modal fade" id="addCustomer" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="modal fade" id="Modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>


    <script type="text/javascript">
        var $table = $('#table'), $edit=$('#edit');$remove = $('#remove'), selections = [];
        var $add = $('#addform');
        var $pwd = $('#editPWD');
        function initTable() {
            $table.bootstrapTable({
                formatSearch:function(){return '请输入用户微信号或者昵称'},
                height: getHeight(),
                clickToSelect:true,
                formatLoadingMessage:function(){
                    return '正在加载，请稍候...';
                },
                formatShowingRows: function (pageFrom, pageTo, totalRows) {
                    return '第 ' + pageFrom + ' - ' + pageTo + ' 记录，共 ' + totalRows + ' 条记录';
                },
                formatRecordsPerPage:function(pageNumber){
                    return '每页 '+pageNumber + ' 记录';
                },
                //queryParams: queryParams, //参数
                columns: [
                    [
                        //{field: 'Number', title: 'Number', formatter: function (value, row, index) {return index + 1;}},
                        {checkbox: true, align: 'center', valign: 'middle'},
                        //{field: 'Sort',title: '排序', align: 'center', valign: 'middle', sortable: true,},
                        {field: 'id',title: '会员id', align: 'center',sortable: true},
                        {field: 'user_name',title: '昵称', align: 'center'},
                        {field: 'balance', title: '余额', align: 'left', halign:'center'},
                        {field: 'phone', title: '手机号码', align: 'center'},
                        {field: 'weixin', title: '微信号', align: 'center'},
                        {field: 'blank', title: '银行卡', align: 'center'},
                        {field: 'status_name', title: '账号状态', align: 'center'},
                        {field: 'amount', title: '佣金总金额', align: 'center',sortable: true},
                        {field: 'created_at', title: '注册时间', align: 'center'},
                        {title:'操作', align:'center',clickToSelect: false, formatter: function (value, row, index)
                            {
                                return '<button name="addCommission" data-id="'+row.id+'" class="btn btn-primary">添加佣金</button>' +
                                    '<button name="addReward" data-id="'+row.id+'" class="btn btn-primary">添加奖励</button>';
                            }}
                    ]
                ]
            });
            // sometimes footer render error.
            setTimeout(function () {
                $table.bootstrapTable('resetView');
            }, 200);
            $table.on('check.bs.table uncheck.bs.table ' +
                    'check-all.bs.table uncheck-all.bs.table', function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                var checkLen = $table.bootstrapTable('getSelections').length;
                if(checkLen == 1){
                    //$edit.attr("data-remote",""+getIdSelections()+'&t=' + Math.random(1000) );
                    $edit.prop('disabled',false);
                    $pwd.prop('disabled',false);

                }else{
                    //$edit.attr("data-remote","javascript:void(0);");
                    $edit.prop('disabled',true);
                    $pwd.prop('disabled',true);
                }
                selections = getIdSelections();
            });
            $remove.click(function () {
                var ids = getIdSelections();
                var param = {ids:ids};
                confirmDelete("/index.php/admin/Customer/delCustomer",param,$table,$remove,"id",ids);
            });
            $add.click(function(){
                $("#addCustomer").modal({
                    remote: "/index.php/admin/Customer/addCustomer"
                    ,backdrop: 'static'
                    ,keyboard: false
                });
            });
            $edit.click(function(){
                $("#addCustomer").modal({
                    remote: "/index.php/admin/Customer/editCustomer?id=" + getIdSelections()+'&t=' + Math.random(1000)
                    ,backdrop: 'static'
                    ,keyboard: false
                });
            });
            $pwd.click(function(){
                $("#addCustomer").modal({
                    remote: "/index.php/admin/Customer/editPWD?id=" + getIdSelections()+'&t=' + Math.random(1000)
                    ,backdrop: 'static'
                    ,keyboard: false
                });
            });
            $table.delegate('[name="addCommission"]','click', function ()
            {
                var id = $(this).attr('data-id');
                $("#Modal").modal({
                    remote: "<?php echo U('/Admin/Customer/addCommission');?>?id="+id,
                    backdrop: 'static',
                    keyboard: false
                });
            });
            $table.delegate('[name="addReward"]','click', function ()
            {
                var id = $(this).attr('data-id');
                $("#Modal").modal({
                    remote: "<?php echo U('/Admin/Customer/addReward');?>?id="+id,
                    backdrop: 'static',
                    keyboard: false
                });
            });
            $("#Modal").on("hidden.bs.modal", function() {
                $table.bootstrapTable('refresh', {});
                $(this).removeData("bs.modal");
            });


            $(window).resize(function () {
                $table.bootstrapTable('resetView', {
                    height: getHeight()
                });
            });

        }

        function getIdSelections(param) {
            return $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id;
            });
        }
        //$("#model").on("hidden.bs.model",function(e){$(this).removeData();});
        $(function () {
            $("#addCustomer").on("hidden.bs.modal", function() {
                $(this).removeData("bs.modal");
                $edit.prop('disabled',true);
                $pwd.prop('disabled',true);
                $table.bootstrapTable('refresh', {silent: true});
            });
            eachSeries(getScript, initTable);
        });

        $('#auto').change(function(){
            search();
        })
        function search(){
            var auto = $('#auto').val();
            var str =  '/index.php/admin/Customer/loadCustomer?auto='+auto;
            $table.bootstrapTable('refresh', {url: str});
        }
    </script>

</body>
</html>