<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '取消维修';
?>
<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>
    <div class="h-center-wrap">
        <div class="h-form">
            <form method="post" id="wechat-form" action="<?=Url::toRoute(['service/delete','id'=>$id,'fid'=>$fid])?>">
                <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
                <input type="hidden" name="openid" value="<?=$openid?>" />
                <input type="hidden" name="type" value="2" />
                <textarea id="service-desc" class="h-area h-m-b-2" placeholder="取消原因" name="text"></textarea>
                <p>&nbsp;</p>
                <button type="button" onclick="subData()" id="wechat-submit" class="h-button">提交</button>
            </form>
        </div>
    </div>

<script>
    function subData()
    {
        var text = document.getElementById('service-desc').value;
        if( !text ){
            alert('取消原因不能为空！');
            return false;
        }else{
            document.getElementById('wechat-form').submit();
        }
    }
</script>