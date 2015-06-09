<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '租机方案管理';
?>

<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['add','url'=>Yii::$app->request->get('url')])?>" >添加</a></li>
        <li class="active"><a href="javascript:void(0)" >列表</a></li>
    </ul>
</div>
<p>&nbsp;</p>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            'attribute'=>'machine_model_id',
            'format'=> 'html',
            'value'=>function($data)
            {
                return Html::a(ConfigBase::getMachineModel($data->machine_model_id),Url::toRoute(['model/view','id'=>$data->machine_model_id]) );
            }
        ],
        'lowest_expense',
        'black_white',
        'colours',
        [
            'attribute'=>'is_show',
            'filter'=>[1=>'显示',0=>'不显示'],
            'value'=>function($data)
            {
                return $data->is_show==1? '显示':'不显示';
            }
        ],
        [
            'attribute'=>'else_attr',
            'headerOptions'=>['style'=>'width:160px'],
            'format' => 'html',
            'value'=>function($data)
            {
                $attr = json_decode($data->else_attr,true);
                $html = [];
                foreach($attr as $row){
                    $html[] = Html::tag('div',$row['name'].' : '.$row['value']);
                }
                return join("\n",$html);
            }
        ],

        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:120px'],
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