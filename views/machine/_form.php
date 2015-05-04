<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use karpoff\icrop\CropImageUpload;
use yii\bootstrap\Modal;
use app\components\MoreattrWidget;


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
    <?= $form->field($model, 'monthly_rent')->textInput() ?>
    <?= $form->field($model, 'function')->textInput(['placeholder'=>'例如：双面复印，网络打印']) ?>
    <?= $form->field($model, 'buy_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>

    <?php
        if( $model->isNewRecord )
           echo  $form->field($model, 'amount')->textInput(['value'=>1]);
    ?>

    <?= $form->field($model, 'else_attr')->textInput(['placeholder'=>'属性名，属性值一一对应']) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?=MoreattrWidget::widget(['targetId'=>'#tblmachine-else_attr','data'=>$model->else_attr])?>