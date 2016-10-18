<?php
use yii\helpers\Html;
$this->title = '维修金额';
?>
<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>

<div class="h-center-wrap">
    <div class="h-form">
        <form method="post" id="wechat-form">
            <input class="h-input" type="text" name="fault_cost" placeholder="维修金额"/>
            <button type="submit" id="wechat-submit" class="h-button">提交</button>
        </form>
    </div>
</div>