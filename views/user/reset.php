<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Alert;
$this->title = '修改密码';
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

    <?= $form->field($model, 'oldPassword')->passwordInput() ?>
    <?= $form->field($model, 'newPassword')->passwordInput() ?>
    <?= $form->field($model, 'acPassword')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton('添加', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>