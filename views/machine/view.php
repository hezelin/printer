<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\ConfigBase;

/* @var $this yii\web\View */
/* @var $model app\models\TblMachine */

$this->title = '机器详情';
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
            return $model->status == 1? Html::a(ConfigBase::getMxStatus($model->status),Url::toRoute(['rent/add','id'=>$model->id]),['class'=>'green']): ConfigBase::getMxStatus($model->status) ;
        }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped detail-view'],
        'attributes' => [
            'id',
            'wx_id',
            'serial_id',
            'brand',
            'type',
            [
                'attribute'=>'price',
                'value' => formatPrice($model->price),
            ],
            [
                'attribute'=>'monthly_rent',
                'value' => formatPrice($model->monthly_rent),
            ],
            'buy_time',
            [
                'attribute'=>'add_time',
                'value' => date("Y-m-d H:i:s",$model->add_time),
                ],
            'rent_time',
            'maintain_time',
            'function',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => getStatus($model),
            ],
        ],
    ]) ?>