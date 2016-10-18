<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
    $this->title = '修改资料';
$this->params['breadcrumbs'][] = $this->title;
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

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'identity_card') ?>
    <?= $form->field($model, 'address') ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton('保存', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>