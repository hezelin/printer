<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

$this->title = '机器列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-2">
        <a href="<?=Url::toRoute(['add'])?>" class="btn btn-info">添加机器</a>
    </div>
    <div class="col-md-10">
        <ul class="nav nav-tabs">
        <li><a href="list?TblMachineSearch[come_from]=1"> 出租 </a></li>
        <li><a href="list?TblMachineSearch[come_from]=2"> 销售 </a></li>
        <li><a href="list?TblMachineSearch[come_from]=3"> 维修 </a></li>
        <li class="active"><a href="#"> 预设机器 </a></li>
        </ul>
    </div>
</div>
<p>&nbsp;</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],               // 系列
            'id',
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=> 'series_id',
                'headerOptions' => ['style'=>'width:60px'],
                'pageSummary'=>true,
                'editableOptions'=> [
                    'formOptions' => ['action' => ['/machine/editable']],
                    'showButtonLabels' => true,
                    'submitButton' => [
                        'label' => '保存',
                        'class' => 'btn btn-primary btn-sm',
                    ],
                ],
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=> 'remark',
                'headerOptions' => ['style'=>'width:60px'],
                'pageSummary'=>true,
                'editableOptions'=> [
                    'formOptions' => ['action' => ['/machine/editable']],
                    'showButtonLabels' => true,
                    'submitButton' => [
                        'label' => '保存',
                        'class' => 'btn btn-primary btn-sm',
                    ],
                ],
            ],
            [
                'attribute'=>'add_time',
                'format'=>['date','php:Y-m-d H:i'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['style'=>'width:140px'],
                'template' => '{update} &nbsp; {delete} &nbsp; {qrcode}',
                'buttons' => [
                    'update' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::toRoute(['update-rent','id'=>$model->id]) ,['title'=>'修改资料']);
                    },
                    'qrcode' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/machine','id'=>$model->id]) ,['title'=>'机器二维码']);
                    },
                ],
            ],
        ],
    ]); ?>