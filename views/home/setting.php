<?php
/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '店铺设置';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="alert alert-info" role="alert">
    1. 店铺名称默认为公众号名称<br/>
    2. 微信入口菜单通过微信自定义菜单展示，点击该菜单可前往店铺首页，请根据需要设置其名称<br/>
<!--    2. 设置触发信息后，当用户输入匹配的关键词后公众号将自动回复设定的单图文消息，其链接为微官网首页。<br/>-->
<!--    3. <span class="red">精确匹配</span>意为用户输入的文字与关键词完全一致才触发，<span class="red">模糊匹配</span>则为输入的文字中包含关键词就会触发。-->
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

<hr>
    <?php
    $form = ActiveForm::begin([
        'id' => 'setting-form',
        'options' => ['id' => 'storesetting-form','class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>

    <?= $form->field($model, 'store_name')->textInput(['value'=>$model->store_name?$model->store_name:Yii::$app->session['wechat']['name']]) ?>
    <?= $form->field($model, 'menu_name')->textInput()->hint('公众号菜单名字') ?>
    <?= $form->field($model, 'phone')->textInput(['placeholder'=>'用户微信端显示为客服电话'])->hint('公众号客服电话') ?>
    <?= $form->field($model, 'address')->textInput() ?>
    <?= $form->field($model, 'style')->dropDownList(['home-default.css'=>'默认']) ?>

    <div class="form-group">
        <div class="h3 col-lg-offset-2 col-lg-9">
            <?= Html::submitButton('提交保存', ['class' => 'col-md-2 btn btn-primary', 'name' => 'setting-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


<script>
    <?php $this->beginBlock('JS_END') ?>
    //样式文件的选择及预览
    $('#tblstoresetting-style').on('change',function(){
        if($(this).val() == ''){
            $('#cssimg').hide();
            return false;
        }
        switch($(this).val()){
            case '1': cssimg = 'home-default.jpg'; break;
            default : cssimg = 'home-default.jpg';
        }
        $('#cssimg').attr('src','/images/'+ cssimg)
        if(!($('#cssimg').is(':visible')))
            $('#cssimg').show();
    });

    <?php $this->endBlock();?>
</script>
<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>