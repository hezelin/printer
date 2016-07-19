<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
$this->title = '电话维修';
$this->params['breadcrumbs'][] = $this->title;
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

if( $tips )
    echo Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => join('<br/>',$tips),
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

    <?= $form->field($fault, 'desc')->hint('越清楚越方便维修员')?>
    <?= $form->field($machine, 'come_from')->dropDownList(\app\models\ConfigBase::$machineOrigin)->hint('尽量完善机型资料') ?>

    <?= $form->field($fault, 'openid')->dropDownList($maintainer)->hint('选择委派维修员,不选则以后再委派')?>
    <?= $form->field($fault, 'type')->dropDownList(\app\models\ConfigBase::$faultStatus)->hint('选择正确故障，方便统计')?>
    <?= $form->field($rent, 'apply_word')->textarea()->label('客户备注')->textInput(['placeholder'=>'客户备注'])->hint('例如：30岁，很礼貌')?>
    <?= $form->field($fault, 'remark')->textarea()->label('维修留言')->textInput(['placeholder'=>'给维修员留言'])->hint('例如：vip用户')?>

    <div id="all-input">
    <div id="all-input-head" class="col-lg-12 alert alert-info">+ 有更多资料，请展开补充资料（可不填写）</div>
    <div id="all-input-wrap">
    <div class="col-lg-12">
        <p class="col-lg-7 text-center">--------------------------- 机器资料 ---------------------------</p>
    </div>

    <?= $form->field($machine, 'series_id')->textInput(['placeholder'=>'输入客户编号'])->hint('例如：GZ001（广州地区客户）') ?>

    <?= $form->field($machine, 'brand')->dropDownList([''=>'选择'] + \app\models\config\ConfigScheme::$brand)->hint('品牌不存在跟我们联系')->label('选择品牌') ?>

    <?= $form->field($machine, 'model_id')->widget(\app\components\ItemDependentWidget::className(),[
        'depend'=>'brand',
        'dataUrl'=>'/ajax-data/model-type'
    ])->hint('型号不存在跟我们联系')?>

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