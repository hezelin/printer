<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\Alert;

$this->title = '活动记录';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Alert::widget() ?>

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

    <?= $form->field($model, 'text')->widget('kucha\ueditor\UEditor',[
        'clientOptions' => [
            'zIndex'=>1029,
            'initialFrameHeight'=>'240',
            'toolbars' => [
                [
                    'fullscreen', 'source', 'undo', 'redo',
                    '|', 'fontsize',
                    'bold', 'italic', 'underline', 'strikethrough', 'removeformat',
                    'formatmatch', 'autotypeset', 'blockquote',
                    '|', 'forecolor', 'backcolor',
                    '|', 'lineheight', 'indent', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify',
                    'insertorderedlist', 'insertunorderedlist',
                    '|','searchreplace','link','horizontal','insertimage','insertvideo','preview'
                ],
            ],
        ],
    ]);?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton('发送', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>

