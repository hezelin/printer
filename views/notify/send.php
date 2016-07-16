<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '发送通知';
$this->params['breadcrumbs'][] = $this->title;

function getSex($sex){
    return $sex==1? '男':($sex==2? '女':'未知');
}
?>

<div id="user-info" class="alert alert-info" role="alert">
    <?php if($user):?>
        <p> <img src="<?= substr($user['headimgurl'],0,-1)?>46" />&nbsp;&nbsp;&nbsp;
            <?= $user['nickname'],' , ',getSex($user['sex']),' , ',$user['country'],$user['province'],$user['city']?>
            &nbsp;&nbsp;
            <span class="small"> <?= date('Y-m-d H:i',$user['subscribe_time'])?>关注</span>
        </p>
        <p>
            <a class="btn btn-success" href="<?=\yii\helpers\Url::toRoute(['wxuser/selectmaintain','url'=>\Yii::$app->request->url])?>">重新指定维修员</a>
            <a class="btn btn-success" href="<?=\yii\helpers\Url::toRoute(['wxuser/select','url'=>\Yii::$app->request->url])?>">重新指定用户</a>
        </p>
    <?php else:?>
        <p>指定用户发送
            <a class="btn btn-success" href="<?=\yii\helpers\Url::toRoute(['wxuser/selectmaintain','url'=>\Yii::$app->request->url])?>">指定维修员</a>
            <a class="btn btn-success" href="<?=\yii\helpers\Url::toRoute(['wxuser/select','url'=>\Yii::$app->request->url])?>">指定用户</a>
        </p>
    <?php endif;?>
</div>

<?php
if( Yii::$app->session->hasFlash('error') )
    echo \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
?>


<div class="row">
    <?php
    $form = ActiveForm::begin([
        'id' => 'add-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>

    <?= $form->field($model, 'text')->textarea(['placeholder'=>'字数不超过140'])?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton('发送', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>
