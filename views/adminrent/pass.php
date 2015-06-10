<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Alert;


    $this->title = '审核资料'
?>

<div class="alert alert-info" role="alert">
    1、租借成功,请填写真实的用户资料<br/>
</div>

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
    <?php
    $form = ActiveForm::begin([
        'id' => 'add-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>

    <?= $form->field($model, 'machine_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => app\models\ConfigBase::getMachineInfo(),
        'language' => 'zh-CN',
        'options' => ['placeholder' => '输入 机器品牌/机器型号/机器系列号'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]);?>

    <?= $form->field($model, 'monthly_rent') ?>
    <?= $form->field($model, 'black_white') ?>

    <?= $form->field($model, 'colours') ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'address')->textarea() ?>
    <?= $form->field($model, 'due_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>
    <?= $form->field($model, 'apply_word')?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton('保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>