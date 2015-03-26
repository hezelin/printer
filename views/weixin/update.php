<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '修改公众号';
?>

<div class="alert alert-info" role="alert">
    1. 在公众平台(开发者中心)申请接口使用的<span class="red"> AppId </span>和
    <span class="red"> AppSecret </span>，然后填入下边表单。<br/>
</div>

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

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'wx_num') ?>
    <?= $form->field($model, 'app_id') ?>
    <?= $form->field($model, 'app_secret') ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
            <?= Html::submitButton('保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>
