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
                return ['value'=> $model->id . '|' . $model->series_id ];
            }
        ],
        'series_id',
        'buy_date',
        'buy_price',
        'maintain_count',
        'rent_count',
        [
            'attribute'=>'type',
            'format'=>'html',
            'value'=>function($data){
                return Html::a($data->machineModel->type,Url::toRoute(['model/view','id'=>$data->model_id]));
            },
//                'value'=>'machineModel.type',
            'header'=>'型号'
        ],
        [
            'attribute'=>'name',
            'value'=>'machineModel.brand.name',
            'header'=>'品牌'
        ],
        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'attribute'=>'status',
            'headerOptions' => ['style'=>'width:110px'],
            'format' => 'html',
            'filter' => ConfigBase::$mxStatus,
            'value' => function($data){
                switch($data->status){
                    case 1: return Html::a(ConfigBase::getMxStatus($data->status),
                        Url::toRoute(['wxuser/select','url'=>Url::toRoute(['adminrent/bings','machine_id'=>$data->id])]),
                        ['class'=>'btn btn-info btn-sm','title'=>'分配租赁用户']);
                    case 3: return Html::tag('span',ConfigBase::getMxStatus($data->status),['class'=>'btn btn-waring btn-sm']);
                    default : return ConfigBase::getMxStatus($data->status);

                }
            }
        ],
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