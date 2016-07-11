<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\bootstrap\Alert;

$this->title = '预设机器二维码';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

if( Yii::$app->session->hasFlash('error') )
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
?>

<div class="alert alert-info" role="alert">
    1、您最多只能生成300个空资料的机器二维码，目前已生成<?=$count?>个
    <p>&nbsp;</p>
    <form class="form-inline" method="post">
        <input name="_csrf" type="hidden" value="<?php echo \Yii::$app->request->csrfToken; ?>"/>
        <div class="form-group">
            <input type="number" name="ahead-code" class="form-control" id="ahead-code" placeholder="还可以生成<?= 300-$count ?>个">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-left: 15px;">点击生成</button>
    </form>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-striped'],
//    'filterModel' => $searchModel,
    'id' => 'my-grid',
    'columns' => [
        [
            'class' => CheckboxColumn::className(),
            'checkboxOptions' => function($model, $key, $index, $column) {
                return ['value'=> $model->id];
            }
        ],
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'id',
        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:120px'],
            'template' => '{qrcode} &nbsp; {delete} &nbsp; {update}',
            'buttons' => [
                'qrcode' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::to(['code/machine','id'=>$model->id]) ,['title'=>'机器码']);
                },
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::to(['machine/force-delete','id'=>$model->id]),[
                        'title'=>'删除',
                        'data-method'=>'post',
                        'data-confirm'=>'确定删除？',
                        'data-pjax'=>0
                    ]);
                },
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::to(['machine/update','id'=>$model->id]) );
                }
            ],
        ],
    ],
]); ?>


<form id="machine-form" method="post" action="<?= Url::toRoute('code/machineall')?>">
    <input id="machine-list" name="list" type="hidden"/>
    <input name="_csrf" type="hidden" value="<?php echo Yii::$app->request->csrfToken; ?>"/>

    <button id="sub-machine" type="button" class="btn btn-primary" onclick="createCode()">批量生成机器码</button>
</form>


<script>

function createCode()
{
    var data = [];
    $("input[name='selection[]']:checked").each(function () {
        data.push( $(this).val() );
    });
//    var data = $('#my-grid').yiiGridView('getSelectedRows');
    if(data.length > 0){
        if(!confirm('确定要打印所选的 '+data.length+' 个机器吗？')) return false;
        $('#machine-list').val(data);
        $('#machine-form').submit();
    }else{
        alert("请选择机器");
    }
}
</script>