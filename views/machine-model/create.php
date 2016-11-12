<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\config\ConfigScheme;

$this->title = '添加型号';
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="tbl-server-info-form">

    <?php $form = ActiveForm::begin([
        'id' => 'my-form',
        'fieldConfig' => [
            'template' => "<div class=\"row\">{label}\n<div class=\"col-lg-8\">{input}</div><div class=\"col-lg-2\">{hint}</div> \n<div class=\"col-lg-8 col-lg-offset-2\">{error}</div></div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'brand')->dropDownList([''=>'选择'] + ConfigScheme::$brand) ?>

    <?= $form->field($model, 'template_model')->widget(\app\components\ItemDependentWidget::className(),[
        'depend'=>'brand',
        'dataUrl'=>'/ajax-data/model-type'
    ])->hint('添加机型之前，查看是否已存在，不区分大小写')?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(ConfigScheme::$type) ?>

    <?= $form->field($model, 'print_speed')->dropDownList(ConfigScheme::$speed) ?>

    <?= $form->field($model, 'color_type')->dropDownList(ConfigScheme::$isColor) ?>

    <?= $form->field($model, 'max_size')->dropDownList(ConfigScheme::$maxSize) ?>

    <?= $form->field($model, 'two_sided')->dropDownList(ConfigScheme::$twoSided) ?>

    <div class="form-group" style="margin:20px 0;">
        <div class="row">
            <div class="col-lg-3">
                <?= Html::submitButton($model->isNewRecord ? '保存资料': '保存修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
