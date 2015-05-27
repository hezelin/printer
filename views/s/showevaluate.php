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

<div class="h-center-wrap">

    <div class="h-form">
        <p>&nbsp;</p>
        <div class="h-row">
            <div class="h-label">评分</div>
            <div class="h-label-input" style="font-size: 20px; font-weight: 600px; color: #FF0000"><?=$model['score']?>分</div>
        </div>
        <div class="h-row">
            <div class="h-label">时间</div>
            <div class="h-label-input" style="width: 100%; overflow: visible"><?=date('Y年m月d日',$model['add_time'])?></div>
        </div>
    </div>
</div>