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
    .icon_dd{
        background:#e6e6e6;
        width:100px;
        overflow:hidden;
        font-size: 60px;
        height:100px;
        line-height:100px;
        margin:0 auto;
        border-radius:50%;
        margin-top:90px;
    }
</style>
<div class="icon_dd aui-iconfont aui-icon-form aui-badge-danger aui-text-center"></div>
<div class="aui-text-center aui-padded-10" style="font-size:14px; margin-top:10px;"><?=$tips?></div>
<?php
if(isset($btnText,$btnUrl) && $btnText && $btnUrl) {
    echo Html::a($btnText, $btnUrl, ['class' => 'aui-btn aui-btn-block aui-btn-outlined aui-bad-danger', 'style'=> 'width:100px; height:34px; line-height:30px; font-size:14px; margin:0 auto; padding:0; border-color:#999; color:#787878;']);
}
echo '<p>&nbsp;</p>';
if(isset($btnText2,$btnUrl2) && $btnText2 && $btnUrl2) {
    echo Html::a($btnText2, $btnUrl2, ['class' => 'aui-btn aui-btn-block aui-btn-outlined aui-bad-danger', 'style'=> 'width:100px; height:34px; line-height:30px; font-size:14px; margin:0 auto; padding:0; border-color:#999; color:#787878;']);
}
?>



<?php if( isset($jumpUrl) && $jumpUrl ):?>
    <script>
        setTimeout("top.location.href='<?=$jumpUrl?>'",3000);
    </script>
<?php endif;?>