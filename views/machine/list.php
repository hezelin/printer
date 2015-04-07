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
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'wx_id',
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
                'headerOptions' => ['style'=>'width:120px'],
                'template' => '{view} &nbsp; {update} &nbsp; {delete} &nbsp; {qrcode}',
                'buttons' => [
                    'qrcode' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::to(['code/machine','id'=>$model->id]) ,['title'=>'机器码']);
                    }
                ],
            ],
        ],
    ]); ?>