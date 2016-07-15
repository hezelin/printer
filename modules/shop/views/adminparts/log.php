<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '配件记录';
$this->params['breadcrumbs'][] = $this->title;

?>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            'attribute'=>'content',
            'format'=> 'html',
            'value'=>function($model) {
                $con = json_decode($model->content, true);
                $html[] = $con['text'];
                if (isset($con['machine_id']))
                    $html[] = \yii\helpers\Html::a('查看机器', Url::toRoute(['/machine/view', 'id' => $con['machine_id']]), ['class' => 'log-link']);

                if (isset($con['fault_id']))
                    $html[] = \yii\helpers\Html::a('查看维修', Url::toRoute(['/service/process', 'id' => $con['fault_id']]), ['class' => 'log-link']);

                return implode(' ，', $html);
            }
        ],
        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i']
        ],
    ],
]);
?>