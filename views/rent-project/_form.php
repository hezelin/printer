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

    <?= $form->field($model, 'brand')->dropDownList([''=>'选择'] + \app\models\config\ConfigScheme::$brand)->hint('品牌不存在跟我们联系') ?>

    <?= $form->field($model, 'machine_model_id')->widget(\app\components\ItemDependentWidget::className(),[
        'depend'=>'brand',
        'dataUrl'=>'/ajax-data/model-type'
    ])->hint('型号不存在跟我们联系')?>

<?= $form->field($model, 'lowest_expense')->textInput(['placeholder'=>'每月最低消费/元，没有最低消费请填0']) ?>
<?= $form->field($model, 'contain_paper')->textInput(['placeholder'=>'月消费包含黑白张数（可省略）']) ?>
<?= $form->field($model, 'black_white')->textInput(['placeholder'=>'黑板纸张多少元每张，例如 0.05']) ?>
<?= $form->field($model, 'colours')->textInput(['placeholder'=>'彩色纸张多少钱每张，机器不支持彩色可不填']) ?>

<?= $form->field($model, 'is_show')->radioList([1=>'显示',0=>'不显示'])?>

    <?= $form->field($model, 'images')->widget(\app\components\UploadimageWidget::className(),[
        'serverUrl'=>Url::toRoute(['/site/image','pathName'=>'scheme']),'imageLimit'=>5
    ])?>

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