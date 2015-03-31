<?php
$this->title = '店铺装修';
use yii\bootstrap\Alert;
?>
请在此处上传轮播图片

<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput() ?>

    <button>Submit</button>

<?php ActiveForm::end() ?>
<?php
Alert::begin([
    'options' => [
         'class' => 'alert-info',
    ],
     'body' => 'Say hello...<br>sdfs',
 ]);
Alert::end();