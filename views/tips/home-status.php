<?php
use yii\helpers\Html;
$this->title = '状态提示';
use app\assets\AuicssAsset;
AuicssAsset::register($this);


//<meta http-equiv="refresh" content="0;url=http://www.baidu.com/">
/*
 * 参数  $tips ，提示文件
 * $btnText 按钮文字
 * $btnUrl 按钮链接
 */
?>
<style>
    body{
        background: #f8f8f8;
    }
    .tips-text{
        padding: 30px 15px;
        border: 1px dashed #ccc;
        margin: 20px 10px;
        font-size: 16px;
        line-height: 24px;
        color: #333;
    }
    .tips-btn1{
        background-color: #3498db;
        color: #fff;
        display: block;
        padding: 8px;
        text-align: center;
        font-size: 18px;
        margin: 15px 10px;
        border-radius: 4px;
        border: 1px solid #3498db;
    }
    .tips-btn2{
        background-color: #fff;
        color: #333;
        border: 1px solid #ddd;
        display: block;
        padding: 8px;
        text-align: center;
        font-size: 18px;
        margin: 15px 10px;
        border-radius: 4px;
    }

</style>
<div class="tips-text">
    <i class="aui-iconfont aui-icon-warn"></i> <?=$tips?>
</div>
<?php
if(isset($btnText,$btnUrl) && $btnText && $btnUrl) {
    echo Html::a($btnText, $btnUrl, ['class' => 'tips-btn1']);
}
if(isset($btnText2,$btnUrl2) && $btnText2 && $btnUrl2) {
    echo Html::a($btnText2, $btnUrl2, ['class' => 'tips-btn2']);
}
?>


<?php if( isset($jumpUrl) && $jumpUrl ):?>
    <script>
        setTimeout("top.location.href='<?=$jumpUrl?>'",3000);
    </script>
<?php endif;?>