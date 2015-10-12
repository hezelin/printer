<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
$this->title = '电话维修';
?>
<style>
    #all-input-head{ cursor: pointer;}
    #all-input-wrap{ display: none;}
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

    <?= $form->field($rent, 'name')->textInput(['placeholder'=>'个人用户 / 公司']) ?>
    <?= $form->field($rent, 'phone')->textInput(['placeholder'=>'手机 / 电话']) ?>
    <?= $form->field($rent, 'address')?>
    <?= $form->field($fault, 'desc')?>
    <?= $form->field($fault, 'openid')->dropDownList($maintainer)?>
    <?= $form->field($fault, 'type')->dropDownList(\app\models\ConfigBase::$faultStatus)?>
    <?= $form->field($rent, 'apply_word')->textarea()->label('用户备注')->textInput(['placeholder'=>'备注用户'])?>
    <?= $form->field($fault, 'remark')->textarea()->label('维修留言')->textInput(['placeholder'=>'给维修员留言'])?>

    <div id="all-input">
    <div id="all-input-head" class="col-lg-12"> <p class="col-lg-5 col-lg-offset-2 alert alert-info">+ 展开补充资料</p></div>
    <div id="all-input-wrap">
    <div class="col-lg-12">
        <p class="col-lg-7 text-center">--------------------------- 机器资料 ---------------------------</p>
    </div>
    <?= $form->field($machine, 'series_id')->textInput(['placeholder'=>'多个序列号用逗号","隔开']) ?>
    <?= $form->field($machine, 'model_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => [0=>'电话维修专用'] + app\models\ConfigBase::getMachineModel(),
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
    <?= $form->field($machine, 'buy_price')->textInput() ?>
    <?= $form->field($machine, 'buy_date')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>
    <?= $form->field($machine, 'come_from')->dropDownList(\app\models\ConfigBase::$machineOrigin) ?>
    <?= $form->field($machine, 'else_attr')->textInput(['placeholder'=>'属性名，属性值一一对应']) ?>

<div class="col-lg-12">
    <p class="col-lg-7 text-center">--------------------------- 租借资料 --------------------------- </p>
</div>
    <?= $form->field($rent, 'monthly_rent')->label('租借月租')->textInput(['placeholder'=>'0']) ?>
    <?= $form->field($rent, 'black_white')->textInput(['placeholder'=>'0']) ?>
    <?= $form->field($rent, 'colours')->textInput(['placeholder'=>'0']) ?>

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

    </div></div><!-- end all-input -->
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($machine->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?=\app\components\MoreattrWidget::widget(['targetId'=>'#tblmachine-else_attr','data'=>''])?>

<script>
<?php $this->beginBlock('JS_END') ?>
    $('#all-input-head').click(function(){
        if($(this).hasClass('all-input-active')){
            $('#all-input-wrap').slideUp();
            $(this).removeClass('all-input-active').children('p').text('+ 展开补充资料');
        }else{
            $('#all-input-wrap').slideDown();
            $(this).addClass('all-input-active').children('p').text('- 点击隐藏');
        }
    });
<?php $this->endBlock();?>
</script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>