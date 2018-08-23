<?php if (!defined('THINK_PATH')) exit();?><div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?php if(empty($_GET['id'])): ?>新增<?php else: ?> 编辑<?php endif; ?>用户</h4>
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form" id="defaultForm" method="post" action="<?php echo ($self); ?>">
        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
        <fieldset>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="user_name">用户名称</label>
                <div class="col-sm-4">
                    <input class="form-control" id="user_name" type="text" name="user_name" value="<?php echo ($info["user_name"]); ?>"/>
                </div>

                <label class="col-sm-2 control-label" >头像</label>
                <div class="col-sm-4">
                    <input type="hidden" id="pic" name="pic" value="<?php echo ($info["pic"]); ?>" />
                    <img src="<?php echo ((isset($info["pic"]) && ($info["pic"] !== ""))?($info["pic"]):'/public/no_photo.png'); ?>" width="100" id="photo_preview" />
                    <input class="upload-btn" type="file" id="btn-upload">
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="balance"><span style="color: red;">*</span>&nbsp;余额</label>
                <div class="col-sm-4">
                    <input class="form-control" id="balance" name="balance"  value="<?php echo ($info["balance"]); ?>" type="text" />
                </div>
                <label class="col-sm-2 control-label">账号状态</label>
                <div class="col-sm-3" style="padding-top:4px;">
                    <input type="radio" value="0" checked="checked" name="status">
                    正常
                    <input type="radio" value="1" <?php if($info['status'] == 1): ?>checked="checked"<?php endif; ?> name="status">
                    冻结
                </div>
            </div>
            <!--<div class="form-group">-->
                <!--<label class="col-sm-2 control-label" for="FullName"><span style="color: red;">*</span>&nbsp;性别</label>-->
                <!--<div class="col-sm-4">-->
                    <!--<input type="radio" value="1" checked="checked" name="sex">-->
                    <!--男-->
                    <!--<input type="radio" value="2" <?php if($info['sex'] == 2): ?>checked="checked"<?php endif; ?> name="sex">-->
                    <!--女-->
                <!--</div>-->
                <!--<label  class="col-sm-2 control-label">会员等级</label>-->
                <!--<div class="col-sm-3">-->
                    <!--<?php echo parse_dropdownlist($info[grade_id],$grade,"grade_id","grade_id","form-control","请选择会员等级");?>-->
                <!--</div>-->
            <!--</div>-->

            <div class="form-group">
                <label class="col-sm-2 control-label" for="phone"><span style="color: red;">*</span>&nbsp;手机号码</label>
                <div class="col-sm-4">
                    <input class="form-control" id="phone" name="phone"  value="<?php echo ($info["phone"]); ?>" type="text" />
                </div>
                <label for="weixin"  class="col-sm-2 control-label">微信号</label>
                <div class="col-sm-3">
                    <input class="form-control" id="weixin" name="weixin"  value="<?php echo ($info["weixin"]); ?>" type="text" />
                </div>
            </div>
            <!--
            <div class="form-group">
                <label class="col-sm-2 control-label" for="amount"><span style="color: red;">*</span>&nbsp;佣金总金额</label>
                <div class="col-sm-4">
                    <input class="form-control" id="amount" name="amount"  value="<?php echo ($info["amount"]); ?>" type="text" />
                </div>
            </div>
            -->
        </fieldset>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
    <button type="button" class="btn btn-primary" id="submit">保存</button>
</div>

<script type="text/javascript" src='/public/Static/uploadifive/Sample/jquery.uploadifive.min.js'></script>

<script type="text/javascript">

    //设置图片上传插件
    $(function () {
        $("#btn-upload").uploadifive({
            height: 25,
            swf: '/Public/Static/uploadify/uploadify.swf',
            uploadScript: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
            width: 80,
            buttonClass: "l-btn",
            buttonText: '上传',
            onUploadComplete: function (file, data) {
                data = $.parseJSON(data);
                if (data.status) {
                    $("#photo_preview").attr("src", data.data);
                    $("#pic").val(data.data);
                }
                else {
                    (data.info);
                }
            }
        });
    });


    $("#submit").click(function(){

        $("#defaultForm").submit();
    })
    $(document).ready(function() {
        $('#defaultForm')

            .bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    LoginName: {
                        message: '账号不可用',
                        validators: {
                            notEmpty: {
                                message: '账号不能为空'
                            },
                            stringLength: {
                                min: 6,
                                max: 30,
                                message: '账号长度6-30'
                            },
                            /*remote: {
                             url: 'remote.php',
                             message: '账号不可用'
                             },*/
                            regexp: {
                                regexp: /^[a-zA-Z0-9_\.]+$/,
                                message: '账号格式不正确'
                            }
                        }
                    }
                }
            })

                .on('success.form.bv', function(e) {
                    // Prevent form submission
                    e.preventDefault();
                    // Get the form instance
                    var $form = $(e.target);
                    // Get the BootstrapValidator instance
                    var bv = $form.data('bootstrapValidator');
                    // Use Ajax to submit form data
                    $.post($form.attr('action'), $form.serialize(), function(result) {
                        if(result.status==1){
                            swal({
                                title:"操作成功！",
                                text:result.info,
                                type:"success"
                            },function(){
                                $('#defaultForm').bootstrapValidator('resetForm', true);$('input').val('');$('#addCustomer').modal('hide');
                            });
                        }else{
                            toastr.warning(result.info);return false;
                        }
                    }, 'json');
                });
    });
</script>