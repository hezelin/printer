<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '成员登录记录';
$this->params['breadcrumbs'][] = ['label'=>'成员管理','url'=>['member']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'login_ip',
            'user_agent',
            [
                'attribute' => 'login_time',
                'value' => function($model)
                {
                    return date('Y-m-d',$model->login_time);
                }

            ],
        ],
    ]); ?>
</div>
