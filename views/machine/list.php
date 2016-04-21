<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\ConfigBase;
use yii\bootstrap\ActiveForm;


$this->title = '机器列表';
?>
<style>
    #my-search-filer{ margin-bottom: 15px;}
    #my-search-filer span{ width:150px; font-weight: 600; font-size: 24px; padding-left: 15px; float: left; height: 40px;}
    #my-search-filer a{margin-right: 15px; display: inline-block; padding: 5px 45px;}
</style>

<div class="row" id="my-search-filer">
    <span>机器来源：</span>
    <a href="list?TblMachineSearch[come_from]=1" class="btn btn-default<?= $_GET['TblMachineSearch']['come_from'] == 1? ' btn-primary':''?>"> 出租 </a>
    <a href="list?TblMachineSearch[come_from]=2" class="btn btn-default<?= $_GET['TblMachineSearch']['come_from'] == 2? ' btn-primary':''?>"> 销售 </a>
    <a href="list?TblMachineSearch[come_from]=3" class="btn btn-default<?= $_GET['TblMachineSearch']['come_from'] == 3? ' btn-primary':''?>"> 维修 </a>
</div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],               // 系列
            'series_id',
            'buy_date',
            'buy_price',
            'maintain_count',
            'rent_count',
            [
                'attribute'=>'type',
                'format'=>'html',
                'value'=>function($data){
                    if( !$data->machineModel ) return '<span class="not-set">(未设置)</span>';
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
//                'filter' => ConfigBase::$mxStatus,
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    ConfigBase::$mxStatus,
                    ['class' => 'form-control', 'prompt' => '状态']
                ),
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
                'attribute'=>'come_from',
                'label'=>'机器来源',
                'filter'=>ConfigBase::$machineOrigin,
                'value'=>function($data){
                    return ConfigBase::getMachineOrigin($data->come_from);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['style'=>'width:140px'],
                'template' => '{view} &nbsp; {update} &nbsp; {delete} &nbsp; {qrcode} &nbsp; {rental}',
                'buttons' => [
                    'update' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::toRoute(['update','id'=>$model->id]) ,['title'=>'修改资料']);
                    },
                    'qrcode' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/machine','id'=>$model->id]) ,['title'=>'机器码']);
                    },
                    'rental' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-stats"></span>',Url::toRoute(['/charts/machine-rental','machine_id'=>$model->id]),['title'=>'租金统计']);
                    }
                ],
            ],
        ],
    ]); ?>