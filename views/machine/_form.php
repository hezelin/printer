<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use karpoff\icrop\CropImageUpload;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model app\models\TblMachine */
/* @var $form yii\widgets\ActiveForm */
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

    <?= $form->field($model, 'serial_id')->textInput(['maxlength' => 10,'placeholder'=>'不填则自动生成']) ?>
    <?= $form->field($model, 'brand')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'price')->textInput() ?>
    <?= $form->field($model, 'monthly_rent')->textInput() ?>
    <?= $form->field($model, 'function')->textInput(['placeholder'=>'例如：双面复印，网络打印']) ?>
    <?= $form->field($model, 'buy_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>

    <?php
        if( $model->isNewRecord )
           echo  $form->field($model, 'amount')->textInput(['value'=>1]);
    ?>

    <?= $form->field($model, 'else_attr')->textInput(['placeholder'=>'属性名，属性值一一对应']) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
Modal::begin([
    'header' => '补充属性',
    'id' => 'Moreattr-modal',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-default add-row">增加一列</button>
        <button id="Moreattr-save" type="button" class="btn btn-primary">保存属性</button>
    ',
]);

echo Html::beginTag('form',['class'=>'form-inline','id'=>'my-city']);


echo '
<div class="form-group m-b-5">
<input type="text" class="form-control" name="attr[]" value="" placeholder="属性名">
&nbsp;&nbsp;
<input type="text" class="form-control" name="attr[]" value="" placeholder="属性值">&nbsp;&nbsp;
<button type="button" class="btn btn-danger del-row"><i class="glyphicon glyphicon-trash"></i> </button>
</div>
';


echo Html::endTag('form');

Modal::end();
?>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var tpl = '<div class="form-group m-b-5"><input type="text" class="form-control" name="attr[]" value="" placeholder="属性名"> &nbsp;&nbsp; <input type="text" class="form-control" name="attr[]" value="" placeholder="属性值">&nbsp;&nbsp; <button type="button" class="btn btn-danger del-row"><i class="glyphicon glyphicon-trash"></i> </button> </div>';

    $('#Moreattr-modal .add-row').click(function(){
        $('#my-city').append(tpl);
    });
    $('#Moreattr-modal').on('click','.del-row',function(){
        $(this).closest('.form-group').remove();
    });

    $('#Moreattr-save').click(function(){
        var tmp = [];
        var i=0;
        var data = {};
        $("#Moreattr-modal input[name='attr[]']").each(function(){
            if( i%2 == 0 ){
                data.name = $(this).val();
            }
            else{
                data.value= $(this).val();
                tmp.push(data );
            }
            i++;
        });
        $('#tblmachine-else_attr').val(JSON.stringify(tmp) );
        $('#Moreattr-modal').modal('hide');
    });

    $('#tblmachine-else_attr').click(function(){
        $('#Moreattr-modal').modal('show');
    });

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>