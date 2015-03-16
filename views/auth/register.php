<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'my-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['placeholder'=>'登录名 / 找回密码']) ?>
    <?= $form->field($model, 'pswd')->passwordInput(['placeholder'=>'密码 6-12 位']) ?>
    <?= $form->field($model, 'acpassword')->passwordInput() ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'areaText')->textInput(['data-target'=>'#city-modal','data-toggle'=>'modal','class'=>'form-control city-text']) ?>
    <?= Html::hiddenInput('RegisterForm[area]','',['id'=>'city-region-id'])?>

    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('注册', ['class' => 'col-md-1 btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>


<?php
Modal::begin([
    'header' => '选择地区',
    'id' => 'city-modal',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="city-save" type="button" class="btn btn-primary">保存选择</button>
    ',
]);

echo Html::beginTag('form',['class'=>'form-inline','id'=>'my-city']);


echo Html::tag(
    'div',
    Html::dropDownList('province','',$province,array('class'=>'form-control th-province','tree-name'=>'city','tree-target'=>'#wrap-city') ),
    ['class'=>'form-group']
);
echo "\r\n&nbsp;&nbsp;&nbsp;";

echo Html::tag(
    'div',
    Html::dropDownList('city','',$city,array('class'=>'form-control th-city','tree-name'=>'region','tree-target'=>'#wrap-region') ),
    ['class'=>'form-group','id'=>'wrap-city']
);

echo "\r\n&nbsp;&nbsp;&nbsp;";

echo Html::tag(
    'div',
    Html::dropDownList('region','',$region,array('class'=>'form-control th-region') ),
    ['class'=>'form-group','id'=>'wrap-region']
);

echo Html::tag(
    'div',
    '',
    ['id'=>'city-status','class'=>'text-danger']
);


echo Html::endTag('form');


Modal::end();
?>

<script>
    <?php $this->beginBlock('JS_END') ?>
    $(function(){
        // 省份、城市、地区 三级联
        $('#my-city').on('change','.th-city,.th-province',function(){
            var treeId = $(this).val();
            if( treeId == '0' ) return false;
            var target = $(this).attr('tree-target');
            $.get('/address/tree',{'id':treeId,'type':$(this).attr('tree-name')},function(data){
                $(target).html( data);
            });
        });
        // 保存地址
        $('#city-save').click(function(){
            var area = '';
            $('#my-city .form-control').each(function(){
                if( $(this).val() == '0'){
                    return false;
                }
                area += $(this).children('option:selected').text()+',';
            })
            var address = area.substr(0,area.lastIndexOf(','));

            /*console.log(address);
            console.log( $('#wrap-region .form-control').val() );*/
            $('.city-text').val( address );
            $('#city-region-id').val($('#wrap-region .form-control').val());
            $('#city-modal').modal('hide');
        });
    });
    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
