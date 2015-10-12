<?php
use yii\helpers\Html;
    $this->title = '维修进度';
?>
<style>
    body{ background-color: #ffffff !important;}
    .h-row{ margin-bottom: 2%; margin-top:0; float: left;}
</style>

<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>
<div class="h-center-wrap">
        <form method="post" id="wechat-form">
            <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
            <input type="hidden"  name="TblServiceEvaluate[fault_id]" value="<?=$id?>" />
            <div class="h-row">
                <div class="h-label">
                    <input type="radio" id="score1" name="score" value="5" checked/>
                </div>
                <label class="h-label" for="score1">很满意</label>
            </div>
            <div class="h-row">
                <div class="h-label">
                    <input type="radio" id="score2" name="score" value="4"  />
                </div>
                <label class="h-label" for="score2">满意</label>
            </div>
            <div class="h-row">
                <div class="h-label">
                    <input type="radio" id="score3" name="score" value="3"  />
                </div>
                <label class="h-label" for="score3">一般</label>
            </div>
            <div class="h-row">
                <div class="h-label">
                    <input type="radio" id="score4" name="score" value="2"  />
                </div>
                <label class="h-label" for="score4">不满意</label>
            </div>
            <div class="h-row">
                <div class="h-label">
                    <input type="radio" id="score5" name="score" value="1"  />
                </div>
                <label class="h-label" for="score5">很不满意</label>
            </div>
            <div class="h-row">&nbsp;</div>
            <button type="submit" id="wechat-submit" class="h-button">提交评价</button>
        </form>
</div>