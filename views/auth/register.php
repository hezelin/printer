<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login" style="padding:0 15px;">
    <?php $form = ActiveForm::begin([
        'id' => 'my-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-5\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'phone')->textInput(['placeholder'=>'登录名 / 找回密码']) ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码 6-12 位']) ?>
    <?= $form->field($model, 'acpassword')->passwordInput() ?>
    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-6">{input}</div></div>',
    ]) ?>

    <div class="form-group">
        <div class="col-md-offset-2 col-md-11">
            <?= Html::submitButton('注册', ['class' => 'col-md-1 btn btn-primary', 'name' => 'login-button']) ?>
            <?= Html::a('登录', Url::toRoute('auth/login'),['class' => 'col-md-offset-1 col-md-2 btn btn-default'] ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>