<?php
    $this->title = '生成机器码';
?>
<style>
    #setting-wrap{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 60px;
        background: #FFFFFF;
        z-index: 10000;
        color: #000;
    }
    #setting-left{
        position: fixed;
        top: 0;
        left: 0;
        width: 20%;
        height: 100%;
        background: #CCCCCC;
        z-index: 10000;
        border-right: 1px solid #666;
        padding: 15px;
    }
    .setting-input{
        width: 75px;
        height: 24px;
        line-height: 24px;
        padding: 0 5px;
        border: 1px solid #333;
        border-radius: 4px;
        font-size: 14px;
    }
    .setting-error{
        border-color: #ff4500;
    }
    .setting-row{
        margin: 5px 0;
    }
    .setting-m{
        margin-top: 30px;
    }
    #upload-img{

    }
</style>
<div id="setting-wrap">
    <div id="setting-left">
        <form class="setting-form">
        <h4>编号设置</h4>
        <div class="setting-row"> 字体颜色：
            <input class="setting-input" type="text" data-vessel="series" data-key="color" data-end="" name="color" value="<?=$data['seriesCss']['color']?>"/></div>
        <div class="setting-row"> 字体大小：
            <input class="setting-input" type="text" data-vessel="series" data-key="font-size" data-end="px" name="size" value="<?=$data['seriesCss']['font-size']?>" />&nbsp;&nbsp;&nbsp;px</div>
        <div class="setting-row"> 顶部距离：
            <input class="setting-input" type="text" data-vessel="series" data-key="top" data-end="px" name="top" value="<?=$data['seriesCss']['top']?>" />&nbsp;&nbsp;&nbsp;px</div>
        <div class="setting-row"> 左部距离：
            <input class="setting-input" type="text" data-vessel="series" data-key="left" data-end="px" name="left" value="<?=$data['seriesCss']['left']?>"/>&nbsp;&nbsp;&nbsp;px</div>

        <h4 class="setting-m">二维码设置</h4>
        <div class="setting-row"> 图片宽度：
            <input class="setting-input" type="text" data-vessel="code" data-key="width" data-end="px" name="code_size" value="<?=$data['codeCss']['width']?>"/>&nbsp;&nbsp;&nbsp;px</div>
        <div class="setting-row"> 顶部距离：
            <input class="setting-input" type="text" data-vessel="code" data-key="top" data-end="px" name="code_top" value="<?=$data['codeCss']['top']?>"/>&nbsp;&nbsp;&nbsp;px</div>
        <div class="setting-row"> 左部距离：
            <input class="setting-input" type="text" data-vessel="code" data-key="left" data-end="px" name="code_left" value="<?=$data['codeCss']['left']?>"/>&nbsp;&nbsp;&nbsp;px</div>

        <h4 class="setting-m">背景图片设置</h4>
        <div class="setting-row"> 图片宽度：
            <input class="setting-input" type="text" data-vessel="bgImg" data-key="width" data-end="px" name="bg_img_size"  value="<?=$data['img']['bgWidth']?>"/>&nbsp;&nbsp;&nbsp;px</div>
        <div class="setting-row"> 背景图片：
            <input id="print-bg-img-url" class="setting-input" type="hidden" data-vessel="bgImg" data-key="img" data-end="" name="color" value="<?=$data['img']['img']?>"/>
            <div id="upload-img" class="btn btn-default">上传图片</div>
        </div>
        </form>
    </div>
</div>

    <div class="col-md-10 col-md-offset-1">
        <button class="btn btn-primary" id="print-save">保存设置</button>
        <a href="/machine/list" class="btn btn-default" style="margin-left: 15px;"><i class="glyphicon glyphicon-qrcode"></i> 返回机器列表</a>
        <button class="btn btn-primary" id="print-btn" style="margin-left: 15px;"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;打印&nbsp;&nbsp;</button>

    </div>

    <div class="col-md-10 col-md-offset-1">
        <div  id="print-wrap" style="position: relative;<?=$data['img']['style']?>">
            <img id="print-bg-img" src="<?=$data['img']['img']?>" <?=$data['img']['width']?>/>
            <div id="qrcode-series" style="position: absolute;<?=$data['series']?>">
                <?= $data['seriesNum'] ?>
            </div>
            <div id="qrcode-img" style="position:absolute;<?=$data['code']?>">
                <img src="<?=$data['qrcodeImgUrl']?>" width="100%"/>
            </div>
        </div>
    </div>


<script>
    <?php $this->beginBlock('JS_END') ?>
    function obj2string(obj){
        var str = '';
        for(var i in obj)
            str += i+':' + obj[i] + ';';
        return str;
    }
    var ex =  /^\d+$/;
        var hasError = 0;
        var series = {};
        var code = {};
        var bgImg = {};
        $('#print-btn').click(function(){
            $('#print-wrap').printArea();
        });

        $('#print-setting-btn').click(function(){
            $('#setting-wrap').show();
        });

        $('.setting-input').blur(function(){
            hasError = 0;
            $('.setting-input').each(function(){
                if($(this).attr('name') !== 'color' && !ex.test( $(this).val() ) ){
                    console.log('必须填入整数' + $(this).val());
                    $(this).addClass('setting-error');
                    alert('必须填入整数');
                    hasError = 1;
                    return false;
                }else{
                    switch ( $(this).attr('data-vessel')){
                        case 'series': series[ $(this).attr('data-key') ] = $(this).val() + $(this).attr('data-end'); break;
                        case 'code': code[ $(this).attr('data-key') ] = $(this).val() + $(this).attr('data-end'); break;
                        case 'bgImg': bgImg[ $(this).attr('data-key') ] = $(this).val() + $(this).attr('data-end'); break;
                    }
                    $(this).removeClass('setting-error');
                }
            })

            if( hasError == 0){
                $('#qrcode-series').attr('style', 'position: absolute;' + obj2string(series) );
                $('#qrcode-img').attr('style', 'position: absolute;' + obj2string(code) );
                $('#print-wrap').attr('style','positon:relative;width:' + bgImg.width  );
                $('#print-bg-img').width( bgImg.width.substr(0,bgImg.width.length - 2) );
            }
        });

//        保存二维码设置
        $('#print-save').click(function(){
            if( hasError == 1 ){
                alert('参数错误！');
                return false;
            }

            hasError = 0;
            $('.setting-input').each(function(){
                if($(this).attr('name') !== 'color' && !ex.test( $(this).val() ) ){
                    console.log('必须填入整数' + $(this).val());
                    $(this).addClass('setting-error');
                    alert('必须填入整数');
                    hasError = 1;
                    return false;
                }else{
                    switch ( $(this).attr('data-vessel')){
                        case 'series': series[ $(this).attr('data-key') ] = $(this).val() + $(this).attr('data-end'); break;
                        case 'code': code[ $(this).attr('data-key') ] = $(this).val() + $(this).attr('data-end'); break;
                        case 'bgImg': bgImg[ $(this).attr('data-key') ] = $(this).val() + $(this).attr('data-end'); break;
                    }
                    $(this).removeClass('setting-error');
                }
            })

            $.post(
                '/code/config',
                {'series':series,'code':code,'bgImg':bgImg},
                function(resp){
                    if(resp.status==1)
                        alert('保存成功！');
                    else alert(resp.msg);
                },'json'
            )
        });


//        上传图片
        var uploadImage = $('#upload-img');
        var Ajax = new AjaxUpload('#upload-img', {
            action: '/image/qrcode',
            name: 'uploadfile',
            onSubmit: function(file, ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('请上传图片文件');
                    return false;
                }
                uploadImage.text('上传中...');
            },
            responseType:'json',
            onComplete: function(file, obj){
                uploadImage.text('上传图片');
                if(obj.status == 1){
                    $('#print-bg-img-url').val(obj.url);
                    $('#print-bg-img').attr('src',obj.url);
                }
                else alert( obj.msg);
            }
        });

    <?php $this->endBlock();?>
</script>
<?php
    $this->registerJsFile('/js/jquery.PrintArea.js',['depends'=>['yii\web\JqueryAsset']]);
    $this->registerJsFile('/js/ajaxupload/ajaxupload.min.js',['depends'=>['yii\web\JqueryAsset']]);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>