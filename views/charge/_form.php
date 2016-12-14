<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\TblMachine */
/* @var $form yii\widgets\ActiveForm */
?>
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
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'black_white')->textInput(['placeholder'=>'填写整数','class'=>'form-control rent-num',
        'data-type'=>'black_white','data-name'=>'last_black','data-contain'=>'contain_paper'])?>

    <?php
        if($hasColour)
            echo $form->field($model, 'colour')->textInput(['placeholder'=>'填写整数','class'=>'form-control rent-num',
                'data-type'=>'colours','data-name'=>'last_colour','data-contain'=>'colours_contain']);
    ?>
    <?= $form->field($model, 'total_money')->textInput(['placeholder'=>'最多2位小数','class'=>'form-control rent-money']) ?>

    <input type="hidden" id="tblrentreport-exceed_money" class="exceed-money" name="TblRentReport[exceed_money]" value="0">
    <input type="hidden" name="TblRentReport[wx_id]" value="<?=$model['wx_id']?>">

    <?= $form->field($model, 'sign_img')->widget(\app\components\UploadimageWidget::className(),[
        'serverUrl'=>Url::toRoute(['image/charge-sign']),'imageLimit'=>1
    ])?>

    <?= $form->field($model, 'next_rent')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    var Rent_price = <?= json_encode([
        'monthly_rent'=>(int)$rent['monthly_rent'],
        'colours'=>(float)$rent['colours'],
        'black_white'=>(float)$rent['black_white'],
        'rent_period'=>(int)$rent['rent_period'],
        'last_black'=> isset($lastCharge['black_white'])? (int)$lastCharge['black_white']:(isset($rent['black_amount'])? $rent['black_amount']:0),
        'last_colour'=> isset($lastCharge['colour'])? (int)$lastCharge['colour']:(isset($rent['colours_amount'])? $rent['colours_amount']:0),
        'contain_paper'=>(int)$rent['contain_paper'],
        'colours_contain' => 0
    ])?>;

    <?php $this->beginBlock('JS_END') ?>
    $('.rent-num').change(function(){
        var money = 0;
        $('.rent-num').each(function(){
            if( $.trim($(this).val()) )
                money += (parseInt($(this).val())-Rent_price[$(this).attr('data-name')]-Rent_price[$(this).attr('data-contain')] * Rent_price.rent_period) * Rent_price[$(this).attr('data-type')] ;

        });
        if(money < 0 )                                          // 计算出的结果不为 负数
            money = 0;
        if( Rent_price[$(this).attr('data-contain')] > 0)               // 月租有包含张数
        {
            $('.exceed-money').val ( money.toFixed(2) )
            money += Rent_price.monthly_rent * Rent_price.rent_period
        }else
        {
            // 总金额小于 月租费用，输出月租金
            if( Rent_price.monthly_rent * Rent_price.rent_period >= money){
                money = Rent_price.monthly_rent * Rent_price.rent_period;
                $('.exceed-money').val(0);
            }
            else{
                $('.exceed-money').val ( (money - Rent_price.monthly_rent * Rent_price.rent_period).toFixed(2) )
            }
        }

        $('.rent-money').val( money.toFixed(2));
    });
    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>