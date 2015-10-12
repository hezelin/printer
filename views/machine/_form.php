<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use app\components\MoreattrWidget;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */
/* @var $model app\models\TblMachine */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    #ui-datepicker-div{
        z-index: 999 !important;
    }
</style>

<?php
if( Yii::$app->session->hasFlash('error') )
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
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

    <?= $form->field($model, 'series_id')->textInput(['placeholder'=>'多个序列号用逗号","隔开']) ?>
    <?= $form->field($model, 'model_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => app\models\ConfigBase::getMachineModel(),
        'language' => 'zh-CN',
        'options' => ['placeholder' => '选择机型 ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'addon' => [
            'append' => [
                'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>',Url::toRoute(['model/add','url'=>Yii::$app->request->url]),[
                    'class' => 'btn btn-success',
                    'title' => '添加模型',
                    'style' => 'padding-bottom:7px;'
                ]),
                'asButton' => true
            ]
        ]
    ]);?>

    <?= $form->field($model, 'buy_price')->textInput() ?>
    <?= $form->field($model, 'buy_date')->widget(dosamigos\datepicker\DatePicker::className(), [
        'language' => 'zh-CN',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]) ?>

    <?= $form->field($model, 'come_from')->dropDownList(\app\models\ConfigBase::$machineOrigin) ?>

    <?php
        if( $model->isNewRecord )
           echo  $form->field($model, 'amount')->textInput(['placeholder'=>1]);
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