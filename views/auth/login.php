<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login" style="padding:0 15px;">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'phone')->textInput(['placeholder'=>'手机号码 / 邮箱']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'6 至 16位密码']) ?>

    <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('登录', ['class' => 'col-md-1 btn btn-primary', 'name' => 'login-button']) ?>
            <?= Html::a('注册', Url::toRoute('auth/register'),['class' => 'col-md-offset-1 col-md-1 btn btn-default'] ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
