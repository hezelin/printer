<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '配件记录';
?>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        'content',
        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i']
        ],
    ],
]);
?>