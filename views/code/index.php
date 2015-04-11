<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use app\models\ConfigBase;

$this->title = '生成机器码';
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'filterModel' => $searchModel,
    'id' => 'my-grid',
    'columns' => [
        [
            'class' => CheckboxColumn::className(),
            'checkboxOptions' => function($model, $key, $index, $column) {
                return ['value'=> $model->id . '|' . $model->serial_id ];
            }
        ],
        'serial_id',
        'type',
        'brand',
        'rent_time',
        'maintain_time',
        [
            'attribute'=>'status',
            'headerOptions' => ['style'=>'width:110px'],
            'format' => 'html',
            'filter' => ConfigBase::$mxStatus,
            'value' => function($data){
                return $data->status == 1? Html::a(ConfigBase::getMxStatus($data->status),Url::toRoute(['rent/add','id'=>$data->id]),['class'=>'green']): ConfigBase::getMxStatus($data->status) ;
            }
        ],
        'remark',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:40px'],
            'template' => '{qrcode}',
            'buttons' => [
                'qrcode' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::to(['code/machine','id'=>$model->id]) ,['title'=>'机器码']);
                }
            ],
        ],
    ],
]); ?>


<form id="machine-form" method="post" action="<?= Url::toRoute('code/machineall')?>">
    <input id="machine-list" name="list" type="hidden"/>
    <input name="_csrf" type="hidden" value="<?php echo Yii::$app->request->csrfToken; ?>"/>

    <button id="sub-machine" type="button" class="btn btn-primary" onclick="createCode()">批量生成机器码</button>
</form>


<script>

function createCode()
{
    var data = [];
    $("input[name='selection[]']:checked").each(function () {
        data.push( $(this).val() );
    });
//    var data = $('#my-grid').yiiGridView('getSelectedRows');
    if(data.length > 0){
        if(!confirm('确定要打印所选的 '+data.length+' 个机器吗？')) return false;
        $('#machine-list').val(data);
        $('#machine-form').submit();
    }else{
        alert("请选择机器");
    }
}
</script>