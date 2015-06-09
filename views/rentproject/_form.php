<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use yii\helpers\Url;
?>
<P>&nbsp;</P>
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
        'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>

<?= $form->field($model, 'machine_model_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \app\models\ConfigBase::getMachineModel(),
    'language' => 'zh-CN',
    'options' => ['placeholder' => '选择机型 ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'addon' => [
        'append' => [
            'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>',Url::toRoute(['model/add','url'=>Yii::$app->request->url]),[
                'class' => 'btn btn-success',
                'title' => '添加机型',
                'style' => 'padding-bottom:7px;'
            ]),
            'asButton' => true
        ]
    ]
]);?>
<?= $form->field($model, 'lowest_expense')->textInput(['placeholder'=>'每月最低消费/元']) ?>
<?= $form->field($model, 'black_white')->textInput(['placeholder'=>'黑板纸张多少元每张，例如 0.05']) ?>
<?= $form->field($model, 'colours')->textInput(['placeholder'=>'彩色纸张多少钱每张，机器不支持彩色可不填']) ?>
<?= $form->field($model, 'is_show')->widget(\kartik\switchinput\SwitchInput::className(),[
    'type' => 1,
    'pluginOptions'=>[
        'onText'=>'是',
        'offText'=>'否'
    ],
    'options'=>[
        'style'=>'margin-left:100px',
    ]
])?>

<?= $form->field($model, 'else_attr')->textInput(['placeholder'=>'属性名，属性值一一对应,是否赠送纸张']) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <div class="m-l-12">
            <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>

<?=\app\components\MoreattrWidget::widget(['targetId'=>'#tblmachinerentproject-else_attr','data'=>$model->else_attr])?>