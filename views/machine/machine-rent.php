<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = '修改资料';
$this->params['breadcrumbs'][] = $this->title;
?>

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
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($rent, 'name')->textInput(['placeholder'=>'个人用户 / 公司'])->hint('个人用户/公司') ?>
    <?= $form->field($rent, 'phone')->textInput(['placeholder'=>'手机 / 电话'])->label('客户手机')->hint('手机号码/电话号码') ?>
    <?= $form->field($rent, 'address')->hint('越详细越方便维修员')?>

    <?= $form->field($machine, 'come_from')->dropDownList(\app\models\ConfigBase::getMachineFrom())->hint('尽量完善机型资料') ?>

    <?= $form->field($rent, 'apply_word')->textarea()->label('客户备注')->textInput(['placeholder'=>'客户备注'])->hint('例如：30岁，很礼貌')?>

    <?= $form->field($machine, 'series_id')->textInput(['placeholder'=>'输入客户编号'])->hint('例如：GZ001（广州地区客户）') ?>

    <?= $form->field($machine, 'brand')->dropDownList([''=>'选择'] + \app\models\config\ConfigScheme::$brand)->hint('品牌不存在跟我们联系')->label('选择品牌') ?>

    <?= $form->field($machine, 'model_id')->widget(\app\components\ItemDependentWidget::className(),[
        'depend'=>'brand',
        'dataUrl'=>'/ajax-data/model-type'
    ])->hint('型号不存在跟我们联系')?>

    <?= $form->field($machine, 'images')->widget(\app\components\UploadimageWidget::className(),[
        'serverUrl'=>\yii\helpers\Url::toRoute(['/site/image','pathName'=>'machine']),'imageLimit'=>5
    ])?>

    <?= $form->field($rent, 'monthly_rent')->label('租借月租')->textInput(['placeholder'=>'0'])->hint('多少元 / 每月,不用填单位') ?>
    <?= $form->field($rent, 'black_white')->textInput(['placeholder'=>'0'])->hint('多少元 / 每张,不用填单位') ?>
    <?= $form->field($rent, 'colours')->textInput(['placeholder'=>'0'])->hint('多少元 / 每张,不用填单位') ?>
    <?= $form->field($rent, 'black_amount')->textInput(['placeholder'=>'必填'])->hint('机器黑白读数初始值')->label('黑白读数')?>
    <?= $form->field($rent, 'colours_amount')->textInput(['placeholder'=>'彩色机必填'])->hint('机器彩色读数初始值')->label('彩色读数')?>


    <?= $form->field($rent, 'due_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>
    <?= $form->field($rent, 'first_rent_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>
    <?= $form->field($rent,'rent_period')->dropDownList([1=>'1个月',3=>'3个月','6'=>'半年','12'=>'1年','24'=>'2年','36'=>'3年','60'=>'5年'])?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($machine->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>