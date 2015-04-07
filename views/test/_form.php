<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblWeixin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-weixin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'wx_num')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 18]) ?>

    <?= $form->field($model, 'app_secret')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'due_time')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'try_time')->textInput() ?>

    <?= $form->field($model, 'pay_time')->textInput() ?>

    <?= $form->field($model, 'pay_total')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'vip_level')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'enable')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
