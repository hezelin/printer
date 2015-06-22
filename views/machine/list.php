<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\ConfigBase;


/* @var $this yii\web\View */
/* @var $searchModel app\models\MachineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '机器列表';
?>

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
                'attribute'=>'come_from',
                'label'=>'机器来源',
                'format'=>'html',
                'filter'=>['非自家','自家'],
                'value'=>function($data){
                    return $data->come_from? '自家':Html::tag('span','非自家',['class'=>'btn btn-warning btn-sm']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['style'=>'width:120px'],
                'template' => '{view} &nbsp; {update} &nbsp; {delete} &nbsp; {qrcode}',
                'buttons' => [
                    'qrcode' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/machine','id'=>$model->id]) ,['title'=>'机器码']);
                    }
                ],
            ],
        ],
    ]); ?>