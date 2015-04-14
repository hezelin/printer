/**
 * Created by Administrator on 2015/4/3.
 */
//自定义trim函数
function ltrim(s){//去左空格
    return s.replace( /^\s*/, "");
}
function rtrim(s){//去右空格;
    return s.replace( /\s*$/, "");
}
function trim(s){
    return rtrim(ltrim(s));
}

//<span data-tdtype="edit" data-field="name" data-unique='1' data-id="<?php echo $info[id];?>" class="tdedit"><?php echo $info[name];?></span>
function deleteimg(obj){
    if(confirm('确定删除该轮播图？')){
        $.ajax({
            //type:'POST',
            url:'delimg?id='+$(obj).attr('data-id'),
            success:function(msg){
                if(msg){
                    $(obj).parent().parent().fadeOut(1000,function(){$(this).remove()}); //移除hr
                }
            }
        });
    }
}

$(document).ready(function(){
        //快速修改插件
        $(document).on('click','span[data-tdtype="edit"]',function() {
        //不应写成$('span[data-tdtype="edit"]').on('click',function() {
            var s_val   = $(this).text(),         //修改前的值
                //s_requesttime=parseInt($("#requesttime").val());//执行前的已请求次数（用于确定隐藏div的状态）
                s_tablename	= ($(this).attr('data-table')!= undefined)?$(this).attr('data-table'):$("#tablename").val(),  //数据表名
                s_name  = $(this).attr('data-field'), //字段名
                s_require= $(this).attr('data-require'),//非空
                s_unique= $(this).attr('data-unique'),//唯一性标志
                s_id    = $(this).attr('data-id'),    //id
                width   = $(this).width()+14,            //文本框宽度
                s_validate= $(this).attr('data-validate');//需要验证的表单形式

            if(s_val=='点击设置') s_val='';
            $('<input type="text" class="lt_input_text" value="'+s_val+'" />').width(width).focusout(function(){
                if(trim($(this).val())=='') $(this).val('点击设置');     //要补trim
                $(this).prev('span').show().text($(this).val());
                if($(this).val()!= s_val ) {   //$(this).val()为修改后的值 && $(this).val()!= '点击设置'&& s_val!= ''
                    //格式验证
                    if(s_require == '1'){   //必须非空
                        if($(this).val() == "点击设置"){
                            alert('该值不允许为空！');
                            $('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val); //回滚
                            $(this).remove();
                            return false;
                        }
                    }
                    if(s_validate=="email"){  //必须为电子邮箱格式
                        if($(this).val()!="" && !$(this).val().match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){
                            //$("#error_tip").text("电子邮箱格式不正确！");
                            //$("#error_tip").slideDown();
                            //setTimeout('$("#error_tip").slideUp()',2000);
                            $('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val); //回滚
                            $(this).remove();
                            return false;
                        }
                    }
                    if(s_validate=="mobile"){  //必须为手机号码格式
                        if($(this).val()!="" && !/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i.test($(this).val())){
                            //$("#error_tip").text("手机号码格式不正确！");
                            //$("#error_tip").slideDown();
                            //setTimeout('$("#error_tip").slideUp()',2000);
                            $('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val); //回滚
                            $(this).remove();
                            return false;
                        }
                    }

                    ////Ajax请求
                    $.getJSON('changetext', {id:s_id, table:s_tablename, field:s_name, unique:s_unique, val:$(this).val()}, function(result){
                        if(result.status == 0) {   //更新出错
                            //$('#loading_tip').slideUp();
                            //$("#error_tip").text(result.msg);  //错误提示
                            //$("#error_tip").slideDown();
                            //setTimeout('$("#error_tip").slideUp()',2000);
                            var errormsg = '';
                            if(result.error == 'GET is empty') errormsg = '修改失败：GET参数为空';
                            else if(result.error == 'Changed 0') errormsg = '值未修改';
                            alert(errormsg);
                            $('span[data-field="' + s_name + '"][data-id="' + s_id + '"]').text(s_val); //回滚
                            return false;
                        }
                        else if(result.status == 1) {   //修改成功
                            //alert('已修改');
                            //$('#loading_tip').slideUp();
                            //$("#success_tip").slideDown(); //操作成功
                            //setTimeout('$("#success_tip").slideUp()',1500);
                            return true;
                        }
                    });
                }
                $(this).remove();
            }).insertAfter($(this)).focus().select();
            $(this).hide();
            return false;
        });

        //全选反选
        $('.toggle_all').on('click', function(){
            $('.toggle').prop('checked', this.checked);  //prop≈attr
            $('.toggle').prop('checked', this.checked);
        });

        //批量删除
        $('#muldelbtn').on('click', function(){
            var checkobj = $('input:checkbox[name=delete]:checked');
            var checkids = [];  //保存选中id的数组
            if(checkobj.length <= 0) {
                alert('请选中要删除的记录');
                return false;
            }
            if(confirm('确定要删除已选的'+checkobj.length+'张图片？')){
                $(checkobj).each(function() {
                    checkids.push($(this).val());
                });
                $.ajax({
                    type: 'POST',
                    data: {"delids":checkids},
                    url : 'muldelcarousel',
                    success:function(msg){
                        if(msg > 0){
                            //alert('已删除'+msg+'条记录');
                            if(msg == checkobj.length){ //成功删除所有记录
                                $(checkobj).each(function() {
                                    $(this).parent().parent().fadeOut(1000,function(){$(this).remove()});
                                });
                            }
                            else location.reload();
                        }
                        else alert('删除失败！');
                    }
                });
            }
        });
});