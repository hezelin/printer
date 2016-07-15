<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
    $this->title = '修改资料';
$this->params['breadcrumbs'][] = ['label'=>'租赁资料','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info" role="alert">
    1、租借成功之后，下面填写真实的用户资料<br/>
    2、状态更改为<span class="red">租借成功</span> 才代表租借成功
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

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'monthly_rent') ?>
    <?= $form->field($model, 'areaText')->textInput(['data-target'=>'#city-modal','data-toggle'=>'modal','class'=>'form-control city-text']) ?>
    <?= Html::hiddenInput('TblRentApply[region]',$model->region,['id'=>'city-region-id'])?>

    <?= $form->field($model, 'address')->textarea() ?>
    <?= $form->field($model, 'due_time')->widget(dosamigos\datepicker\DatePicker::className(), [
        'language' => 'zh-CN',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]) ?>
    <?= $form->field($model, 'apply_word')?>
    <?= $form->field($model, 'status')->dropDownList(\app\models\ConfigBase::$rentStatus) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <div class="m-l-12">
                <?= Html::submitButton('保存修改', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
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
        <?php $this->endBlock();?>
    </script>

<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>