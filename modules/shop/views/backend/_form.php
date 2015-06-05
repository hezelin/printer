<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\MoreattrWidget;

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

    <?= $form->field($model, 'name')->textInput(['placeholder'=>'商品名称']) ?>
    <?= $form->field($model, 'market_price')?>
    <?= $form->field($model, 'price')?>
    <?= $form->field($model, 'category_id')?>
    <?= $form->field($model, 'cover_images')->widget(\app\components\UploadimageWidget::className(),['imageLimit'=>5])?>


    <?= $form->field($model, 'add_attr')->textInput(['placeholder'=>'属性名，属性值一一对应']) ?>

    <?= $form->field($model, 'describe')->widget('kucha\ueditor\UEditor',[
            'clientOptions' => [
                'zIndex'=>1029,
                'toolbars' => [
                    [
                        'fullscreen', 'source', 'undo', 'redo',
                        '|', 'fontsize',
                        'bold', 'italic', 'underline', 'strikethrough', 'removeformat',
                        'formatmatch', 'autotypeset', 'blockquote',
                        '|', 'forecolor', 'backcolor',
                        '|', 'lineheight', 'indent', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify',
                        'insertorderedlist', 'insertunorderedlist',
                        '|','searchreplace','link','horizontal','insertimage','preview'
                    ],
                ]
    ]]);?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?=MoreattrWidget::widget(['targetId'=>'#tblproduct-add_attr','data'=>$model->add_attr])?>