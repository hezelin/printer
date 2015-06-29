<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '配件备注';
?>
<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>

<div class="h-form">
    <form method="post" id="wechat-form">
        <textarea id="service-desc" class="h-area h-m-b-2" placeholder="备注消息" name="content"></textarea>
        <p style="float: left; height: 30px;">&nbsp;</p>
        <button type="submit" id="wechat-submit" class="h-button">提交备注</button>
    </form>
</div>