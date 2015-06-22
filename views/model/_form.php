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

<?= $form->field($model, 'brand_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => $list,
    'language' => 'zh-CN',
    'options' => ['placeholder' => '选择品牌 ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'addon' => [
        'append' => [
            'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>',Url::toRoute(['machinebrand/add','url'=>Yii::$app->request->url]),[
                'class' => 'btn btn-success',
                'title' => '添加品牌',
                'style' => 'padding-bottom:7px;'
            ]),
            'asButton' => true
        ]
    ]
]);?>
<?= $form->field($model, 'buy_date')->widget(\yii\jui\DatePicker::classname(), [
    'dateFormat' => 'yyyy-MM-dd',
    'options' => [
        'class' => 'form-control',
    ]
]) ?>
<?= $form->field($model, 'type')->textInput(['maxlength' => 50]) ?>
<?= $form->field($model, 'function')->textInput(['placeholder'=>'强调显示，显示位置在前面']) ?>
<?= $form->field($model, 'is_color')->radioList(['1'=>'黑白','2'=>'彩色（包含黑白）'])?>

<?= $form->field($model, 'cover_images')->widget(\app\components\UploadimageWidget::className(),[
    'serverUrl'=>Url::toRoute(['image/machine']),'imageLimit'=>5
])?>

<?= $form->field($model, 'else_attr')->textInput(['placeholder'=>'选填']) ?>

<?= $form->field($model, 'describe')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        'zIndex'=>1029,
        'initialFrameHeight'=>'240',
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo',
                '|', 'fontsize',
                'bold', 'italic', 'underline', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote',
                '|', 'forecolor', 'backcolor',
                '|', 'lineheight', 'indent', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify',
                'insertorderedlist', 'insertunorderedlist',
                '|','searchreplace','link','horizontal','insertimage','insertvideo','preview'
            ],
        ],
    ],
]);?>
<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <div class="m-l-12">
            <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>

<?=\app\components\MoreattrWidget::widget(['targetId'=>'#tblmachinemodel-else_attr','data'=>$model->else_attr])?>