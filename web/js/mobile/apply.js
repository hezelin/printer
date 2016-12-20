/**
 * Created by harry on 2015/9/10.
 * 故障申请、故障确认
 */
var images = {
    localId:[],
    serverId:[]
};

var voice_localId,t,require_img = require_img || 0;

$(function(){
    //做时间计时
    function get_plus_time(){
        var time = (parseInt($('#voice-time').text())+1>60)?60:parseInt($('#voice-time').text())+1;
        if(time<10){
            $('#voice-time').text('0'+time);
        }else{
            $('#voice-time').text(time);
        }
    }

    function get_less_time(){
        var time = parseInt($('#voice-time').text())-1;
        $('#voice-wrap').removeClass('voice-play').addClass('voice-stop');
        if(time<10)
            $('#voice-time').text('0'+time);
        else
            $('#voice-time').text(time);

        //播放时间结束
        if(time<0){
            var total_time = $('#voice-time').attr('data-time');
            $('#voice-wrap').removeClass('voice-stop').addClass('voice-play');
            if(total_time<10){
                $('#voice-time').text('0'+total_time);
            }else{
                $('#voice-time').text(total_time);
            }
            clearInterval(t);
        }
    }

    //录音控制
    $('#voice-wrap').click(function(){
        //3种状态 1.开始录音  2.停止录音   3.播放录音  4、停止播放
        var obj = $(this);
        var value = $(obj).attr('data-value');
        //判定 当前状态
        //开始录音
        if(value==1){
            $('#voice-label').text('录音中...');
            wx.startRecord({
                cancel: function () {
                    alert('用户拒绝授权录音，如果您想重新录音，请刷新当前页面！');
                    return false;
                }
            });
            t = setInterval(get_plus_time,1000);
            obj.attr('data-value',2);
            obj.addClass('voice-stop');
        }
        //停止录音
        if(value==2){
            wx.stopRecord({
                success: function (res) {
                    voice_localId = res.localId;
                    obj.attr('data-value',3);
                    obj.removeClass('voice-stop').addClass('voice-play');
                    $('#voice-label').text('点击播放');
                    obj.attr('data-time',$('#voice-time').text());
                    $('#voice-del').show();
                    clearInterval(t);
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                    obj.attr('data-value',1);
                    obj.removeClass('voice_stop');
                    $('#voice-time').text('00');
                    clearInterval(t);
                }
            });
        }
        //播放录音
        if(value==3){
            obj.attr('data-value',4);
            obj.removeClass('voice-play').addClass('voice-playing');
            $('#voice-label').text('播放中...');
            wx.playVoice({
                localId: voice_localId
            });
            t = setInterval(get_less_time,1000);
        }

        //停止播放
        if(value ==4){
            wx.stopVoice({
                localId: voice_localId
            });
            obj.attr('data-value',3);
            obj.removeClass('voice-playing').addClass('voice-play');
            $('#voice-label').text('点击播放');
            $('#voice-time').text( obj.attr('data-time'));
            clearInterval(t);
        }
    });


    $('#voice-del').click(function(){
        //正在播放中，停止播放
        if( parseInt($('#voice-wrap').attr('data-value')) == 4 ){
            wx.stopVoice({
                localId: voice_localId
            });
            clearInterval(t);
        }
        voice_localId = undefined;
        $(this).hide();
        $('#voice-wrap').removeClass('voice-play voice-playing voice-start voice-stop').attr('data-value',1);
        $('#voice-time').text('00');
        $('#voice-label').text('点击录音');
        $('#service-voice').val('');
    });

    $("#upload-img").show();

    $("#upload-img").click(function(){
        wx.chooseImage({
            success: function (res) {
                var tmp = images.localId.concat(res.localIds);
                if( tmp.length > 10){
                    alert('不能超过10张图片！')
                    return false;
                }
                images.localId = tmp;
                var imgs = '';
                for(var i in images.localId)
                    imgs += '<div class="h-img-box"><img src="'+ images.localId[i]+ '"/><em>-</em></div>';
                $("#img-show-wrap").html(imgs);
            }
        });
    });

//        删除数组指定元素
    function delEle(arr,val)
    {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] == val){
                arr.splice(i,1);
                return arr;
            }
        }
        return arr;
    }

//        删除图片
    $('#img-show-wrap').on('click','.h-img-box',function(){
        var val = $(this).children('img').attr('src');
        $(this).remove();
        images.localId = delEle(images.localId,val);
    });
//        提交表单
    $("#wechat-submit").click(function(){
        var i = 0, length = images.localId.length;

        //[20161219 modify
        //if (images.localId.length == 0){
        //    alert('图片不能为空！');
        //    return false;
        //}
        var flag = 0;
        //alert(length);
        if($('#service-desc').val() != '' || images.localId.length != 0 || voice_localId != undefined)//判断文字、图片和语音是否为空
            flag = 1;


        if(flag != 1) {
            alert('文字、图片和语音至少选择一种提交！');
            return false;
        }
        //20161219]

        if( $('#voice-wrap').attr('data-value') == '2'){
            alert('正在录音中....');
            return false;
        }

        function upload() {
            wx.uploadImage({
                localId: images.localId[i],
                success: function (res) {
                    i++;
                    images.serverId.push(res.serverId);
                    if(i<length)
                        upload();
                    if(i >= length){
                        document.getElementById("service-imgid").value=images.serverId.join("|");
                        document.getElementById("wechat-form").submit();
                    }
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }

        if( voice_localId !=  undefined ){
            wx.uploadVoice({
                localId: voice_localId,
                isShowProgressTips: 1, // 默认为1，显示进度提示
                success: function (res) {
                    $('#service-voice').val( res.serverId );
                    $('#service-voice-len').val( $('#voice-wrap').attr('data-time') );
                    if( images.localId.length > 0 ){                // 提交之前 上传图片
                        upload();
                    }else{
                        document.getElementById("wechat-form").submit();
                    }
                }
            });
        }else{
            if( images.localId.length > 0 ){                // 提交之前 上传图片
                upload();
            }else{
                document.getElementById("wechat-form").submit();
            }
        }
    });

});