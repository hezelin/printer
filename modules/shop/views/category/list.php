<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '我的公众号';
?>

<div class="alert alert-info" role="alert">
    上传的宝贝有可能<span class="red">引用</span>到类目，请慎重使用删除功能<br/>
</div>

<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['add','url'=>Yii::$app->request->get('url')])?>" >添加</a></li>
        <li class="active"><a href="javascript:void(0)" >列表</a></li>
    </ul>
</div>
<p>&nbsp;</p>
<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        'name',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{update} &nbsp; {delete}',
            'buttons' => [
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::toRoute(['update','id'=>$key,'url'=>Yii::$app->request->get('url')]),['title'=>'修改']);
                },
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::toRoute(['delete','id'=>$key,'url'=>Yii::$app->request->get('url')]),[
                        'title'=>'删除',
                        'data-confirm'=>'确定删除？',
                        'data-pjax'=>0,
                        'data-method'=>'post'
                    ]);
                },
            ]
        ]
    ],
]);

?>