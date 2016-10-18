<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\ConfigBase;

$this->title = '机器列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-2">
        <a href="<?=Url::toRoute(['add'])?>" class="btn btn-info">添加机器</a>
    </div>
    <div class="col-md-10">
        <ul class="nav nav-tabs">
        <li<?= isset($_GET['TblMachineSearch']['come_from']) && $_GET['TblMachineSearch']['come_from'] != 1? '':' class="active"'?>><a href="list?TblMachineSearch[come_from]=1"> 出租 </a></li>
        <li<?= isset($_GET['TblMachineSearch']['come_from']) && $_GET['TblMachineSearch']['come_from'] == 2? ' class="active"':''?>><a href="list?TblMachineSearch[come_from]=2"> 销售 </a></li>
        <li<?= isset($_GET['TblMachineSearch']['come_from']) && $_GET['TblMachineSearch']['come_from'] == 3? ' class="active"':''?>><a href="list?TblMachineSearch[come_from]=3"> 维修 </a></li>
        <li><a href="pre-list?TblMachineSearch[come_from]=4"> 预设机器 </a></li>
        </ul>
    </div>
</div>
<p>&nbsp;</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'filterModel' => $searchModel,
        'columns' => [
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
            'buy_date',
            'buy_price',
            'maintain_count',
            'rent_count',
            'model_name',
            'brand_name',
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
                            Url::toRoute(['wxuser/select','url'=>Url::toRoute(['/admin-rent/bings','machine_id'=>$data->id])]),
                            ['class'=>'btn btn-info btn-sm','title'=>'分配租赁用户']);
                        case 3: return Html::tag('span',ConfigBase::getMxStatus($data->status),['class'=>'btn btn-waring btn-sm']);
                        default : return ConfigBase::getMxStatus($data->status);

                    }
                }
            ],
            [
                'attribute'=>'come_from',
                'label'=>'机器分类',
                'filter'=>ConfigBase::$machineOrigin,
                'value'=>function($data){
                    return ConfigBase::getMachineOrigin($data->come_from);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['style'=>'width:140px'],
                'template' => '{view} &nbsp; {update} &nbsp; {delete} &nbsp; {qrcode} <br/>{rental} &nbsp; {fault}',
                'buttons' => [
                    'update' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::toRoute(['update','id'=>$model->id]) ,['title'=>'修改资料']);
                    },
                    'qrcode' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/machine','id'=>$model->id]) ,['title'=>'机器二维码']);
                    },
                    'rental' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-stats"></span>',Url::toRoute(['/charts/machine-rental','machine_id'=>$model->id]),['title'=>'租金统计']);
                    },
                    'fault' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-screenshot"></span>',Url::toRoute(['/service/list','machine_id'=>$model->id]) ,['title'=>'机器维修记录']);
                    },
                ],
            ],
        ],
    ]); ?>