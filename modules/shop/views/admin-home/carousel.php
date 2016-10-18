<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Alert;
use yii\widgets\ActiveForm;
use app\models\ConfigBase;
$this->title = '商城轮播图设置';
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
if( Yii::$app->session->hasFlash('success') )
    echo Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => Yii::$app->session->getFlash('success'),
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

    <?= $form->field($model, 'images')->widget(\app\components\UploadimageWidget::className(),[
        'serverUrl'=>Url::toRoute(['/site/image','pathName'=>'carousel']),'imageLimit'=>5
    ])?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>