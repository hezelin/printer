<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use app\components\MoreattrWidget;
use yii\bootstrap\Alert;

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
if( Yii::$app->session->hasFlash('success') )
    echo Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => Yii::$app->session->getFlash('success'),
    ]);
?>

<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'brand')->dropDownList([''=>'选择'] + \app\models\config\ConfigScheme::$brand)->hint('品牌不存在跟我们联系')->label('选择品牌') ?>

    <?= $form->field($model, 'model_id')->widget(\app\components\ItemDependentWidget::className(),[
        'depend'=>'brand',
        'dataUrl'=>'/ajax-data/model-type'
    ])->hint('型号不存在,&nbsp; &nbsp;<a class="btn btn-warning btn-sm" href="/machine-model/create">点击添加</a>')?>

    <?= $form->field($model, 'buy_price')->textInput() ?>
    <?= $form->field($model, 'buy_date')->widget(dosamigos\datepicker\DatePicker::className(), [
        'language' => 'zh-CN',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]) ?>

    <?= $form->field($model, 'series_id')->textInput() ?>

    <?= $form->field($model, 'images')->widget(\app\components\UploadimageWidget::className(),[
        'serverUrl'=>Url::toRoute(['/site/image','pathName'=>'machine']),'imageLimit'=>5
    ])?>

    <?= $form->field($model, 'come_from')->dropDownList(\app\models\ConfigBase::$machineOrigin) ?>

    <?= $form->field($model, 'remark')->textarea(['rows'=>4,'placeholder'=>'机器特点可备注，可省略']) ?>

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