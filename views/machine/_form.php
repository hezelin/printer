<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TblMachine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'serial_id')->textInput(['maxlength' => 10,'placeholder'=>'不填则自动生成']) ?>
    <?= $form->field($model, 'brand')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'buy_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>
    <?= $form->field($model, 'depreciation')->dropDownList([10=>'全新',9=>'9成',8=>'8成',7=>'7成',6=>'6成',5=>'5成',4=>'4成',3=>'3成',2=>'2成',1=>'1成']) ?>
    <?= $form->field($model, 'remark')->textInput() ?>

    <?php
        if( $model->isNewRecord )
           echo  $form->field($model, 'amount')->textInput(['value'=>1]);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
