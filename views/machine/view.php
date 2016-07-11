<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '机器详情';
$this->params['breadcrumbs'][] = ['label'=>'机器列表','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
<?php

    $btns[] = Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary','role'=>'button']);
    $btns[] = Html::a('删除', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'role'=>'button',
        'data' => [
            'confirm' => '确定要删除此选项吗?',
            'method' => 'post',
        ],
    ]);
    $btns[] = Html::a('二维码', Url::toRoute(['code/machine', 'id' => $model->id ]), ['class' => 'btn btn-info','role'=>'button']);

    echo Html::tag('div',implode("\n",$btns),['class'=>'btn-group']);
?>
</p>

    <?php

        function formatPrice($price){
            return '￥' . $price . '元';
        }

        function getStatus($model){
            return $model->status == 1? Html::a(ConfigBase::getMxStatus($model->status),
                Url::toRoute(['wxuser/select','url'=>Url::toRoute(['adminrent/bings','machine_id'=>$model->id])]),
                ['class'=>'btn btn-info btn-sm','title'=>'分配租赁用户']):
                ConfigBase::getMxStatus($model->status) ;
        }

        /*function getFrom($model){
            return $model->come_from? '自家':'非自家';
        }*/

        function formatImage($model){
            return '<img src="'.$model->cover.'" width=100 >';
        }


    ?>
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped detail-view'],
        'attributes' => [
            'id',
            'series_id',
            [
                'attribute'=>'model_id',
                'label' => '品牌机型',
                'format'=>'html',
                'value'=>$model->brand_name.','.$model->model_name,
            ],
            [
                'attribute'=>'buy_price',
                'value' => formatPrice($model->buy_price),
            ],
            'buy_date',
            [
                'attribute'=>'add_time',
                'value' => date("Y-m-d H:i:s",$model->add_time),
                ],
            'rent_count',
            'maintain_count',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => getStatus($model),
            ],
            [
                'attribute' => 'come_from',
                'label'=>'机器来源',
                'value' => ConfigBase::getMachineOrigin($model->come_from),
            ],
        ],
    ]) ?>