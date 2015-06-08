<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
?>
<P>&nbsp;</P>
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
        'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>

<?= $form->field($model, 'name')->textInput(['placeholder'=>'品牌名称']) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <div class="m-l-12">
            <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>