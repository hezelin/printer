<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use app\components\MoreattrWidget;


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

    <div class="form-group field-tblmachine-cover">
        <label class="col-lg-2 control-label" for="tblmachine-cover">封面图片</label>
        <div class="col-lg-5">
            <input type="hidden" id="tblmachine-cover" class="form-control" name="TblMachine[cover]" value="<?=$model->cover?>">
            <div id="image-ajaxupload">上传图片</div>
            <img id="image-show" width="200" src="<?=$model->cover?>" />
        </div>
        <div class="col-lg-5">
            <div class="help-block"></div>
        </div>
    </div>

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

<?=MoreattrWidget::widget(['targetId'=>'#tblmachine-else_attr','data'=>$model->else_attr])?>

<?php

$this->registerJsFile('js/ajaxupload/ajaxupload.min.js',[
    'position' => \yii\web\View::POS_END ,'depends'=>[
        'yii\web\JqueryAsset'
    ]
]);

?>
<script>
    <?php $this->beginBlock('JS_AJAXUPLOAD') ?>
    upload('#image-ajaxupload','#tblmachine-cover','#image-show');
    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_AJAXUPLOAD'],\yii\web\View::POS_READY);
?>

<script>
    function upload($this,$url,$img){
        var btnUpload=$($this);
        var status=$($this);
        new AjaxUpload(btnUpload, {
            action: '<?=Url::toRoute(['image/machine'])?>',
            name: 'uploadfile',
            onSubmit: function(file, ext){

                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    // extension is not allowed
                    status.text('请上传图片文件');
                    return false;
                }
                status.text('正在上传中...');
            },
            onComplete: function(file, response){
                status.text('上传完成');
                var obj=eval("("+response+")");
                if(obj.status == 1){
//                    status.html('<img src="'+obj.url+'"/>');
                    if($url) $($url).val( obj.url );
                    $($img).attr('src',obj.url);
                    return true;
                }
                else status.text( obj.msg);
            }
        });
    }
</script>