<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Alert;
    $this->title = '审核资料';

$this->params['breadcrumbs'][] = ['label'=>'租赁资料','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="alert alert-info" role="alert">
    <?=$tips?>
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
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>
<?php

if( $type =='allot' )
    echo $form->field($model, 'machine_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => app\models\ConfigBase::getMachineInfo(),
        'language' => 'zh-CN',
        'options' => ['placeholder' => '输入 机器品牌/机器型号/机器系列号'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ])->hint('给用户分配机器');
?>

    <?= $form->field($model, 'monthly_rent')->textInput(['placeholder'=>'多少元 / 每月'])->hint('多少元 / 每月') ?>
    <?= $form->field($model, 'black_white')->textInput(['placeholder'=>'单位为元 / 每张，例如：0.02  代表2分钱'])->hint('多少元 / 每张')?>

    <?= $form->field($model, 'colours')->textInput(['placeholder'=>'单位为元 / 每张，例如：0.04  代表4分钱'])->hint('多少元 / 每张')?>
    <?= $form->field($model, 'black_amount')->textInput(['placeholder'=>'必填'])->hint('机器黑白读数初始值')->label('黑白读数')?>
    <?= $form->field($model, 'colours_amount')->textInput(['placeholder'=>'彩色机必填'])->hint('机器彩色读数初始值')->label('彩色读数')?>
    <?= $form->field($model, 'name')->textInput(['placeholder'=>'用户名字，个人 / 公司'])->hint('个人 / 公司')?>
    <?= $form->field($model, 'phone')->hint('真实手机号码') ?>
    <?= $form->field($model, 'due_time')->widget(dosamigos\datepicker\DatePicker::className(), [
        'language' => 'zh-CN',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ])->hint('合同快到期提醒') ?>
    <?= $form->field($model, 'first_rent_time')->widget(dosamigos\datepicker\DatePicker::className(), [
        'language' => 'zh-CN',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ])->hint('到期自动提醒收租') ?>
    <?= $form->field($model,'rent_period')->dropDownList([1=>'1个月',3=>'3个月','6'=>'半年','12'=>'1年','24'=>'2年','36'=>'3年','60'=>'5年'])->hint('到期自动提醒收租')?>
    <?= $form->field($model, 'address')->textarea(['placeholder'=>'尽量填写详细的，方便维修员'])->hint('尽量填写详细，方便维修') ?>
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