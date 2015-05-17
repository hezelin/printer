<?php
use yii\helpers\Html;
    $this->title = '维修进度';
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
        <form method="post" id="wechat-form">
            <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
            <input type="hidden" id="service-imgid" class="h-input" name="TblMachineService[imgid]" />
            <input type="hidden"  name="TblServiceEvaluate[fault_id]" value="<?=$id?>" />
            <p>&nbsp;</p>
            <div class="h-row">
                <div class="h-label">态度</div>
                <?= Html::dropDownList('TblServiceEvaluate[attitude]','',['糟糕','一般','满意'],['class'=>'h-label-input'])?>
            </div>
            <div class="h-row">
                <div class="h-label">速度</div>
                <?= Html::dropDownList('TblServiceEvaluate[speed]','',['比较慢','一般','比较快'],['class'=>'h-label-input'])?>
            </div>
            <div class="h-row">
                <div class="h-label">质量</div>
                <?= Html::dropDownList('TblServiceEvaluate[quality]','',['没修好','正常','非常好'],['class'=>'h-label-input'])?>
            </div>
            <br/>
            <br/>
            <button type="submit" id="wechat-submit" class="h-button">提交评价</button>
        </form>
    </div>
</div>