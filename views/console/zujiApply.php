<?php
use yii\grid\GridView;
$this->title = '我的公众号';
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'name',
        'phone',
        'email',
        'company',
        'info',
        [
            'attribute'=>'create_at',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
    ],
]);

?>