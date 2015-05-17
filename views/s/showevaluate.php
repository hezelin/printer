<?php
use yii\helpers\Html;
    $this->title = '用户评价';
?>
<style>
    body{ background-color: #ffffff !important;}
</style>

<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>
<?php
    $attitude = ['糟糕','一般','满意'];
    $speed = ['比较慢','一般','比较快'];
    $quality = ['没修好','正常','非常好'];
?>
<div class="h-center-wrap">

    <div class="h-form">
        <p>&nbsp;</p>
        <div class="h-row">
            <div class="h-label">态度</div>
            <div class="h-label-input"><?=$attitude[ $model['attitude'] ];?></div>
        </div>
        <div class="h-row">
            <div class="h-label">速度</div>
            <div class="h-label-input"><?=$speed[ $model['speed'] ];?></div>
        </div>
        <div class="h-row">
            <div class="h-label">质量</div>
            <div class="h-label-input"><?=$quality[ $model['quality'] ];?></div>
        </div>
    </div>
</div>