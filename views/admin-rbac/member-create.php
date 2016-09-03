<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = '创建成员';
$this->params['breadcrumbs'][] = ['label'=>'成员管理','url'=>['member']];
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
    <?= $form->field($model, 'name')->textInput(['placeholder'=>'成员真实姓名']) ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码 6-12 位']) ?>
    <?= $form->field($model, 'role')->dropDownList(\app\rbac\Config::$roleName)?>
    <?= $form->field($model, 'weixin_id')->dropDownList($weixinIds)?>

    <div class="form-group">
        <div class="col-md-offset-2 col-md-11">
            <?= Html::submitButton('创建', ['class' => 'col-md-1 btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>