<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '成员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-user-index">

    <p>
        <?= Html::a('创建成员', ['member-create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'phone',
            'name',
            [
                'attribute'=>'role',
                'value' => function($model){
                    return \app\rbac\Config::getRoleName($model->role);
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('Y-m-d H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('Y-m-d H:i',$model->updated_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['style'=>'width:160px'],
                'template' => '{update} &nbsp; {delete}',
                'buttons' => [
                    'update' => function($url,$model,$key){
                        return Html::a('<span class="btn btn-sm btn-info">修改</span>',$url);
                    },
                    'delete' => function($url,$model,$key){
                        return Html::a('<span class="btn btn-sm btn-danger">删除</span>',$url,[
                            'data-method'=>'post',
                            'data-confirm'=>'确定删除？',
                            'data-pjax'=>0
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
