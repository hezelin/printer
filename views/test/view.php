<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TblWeixin */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Weixins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-weixin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'name',
            'wx_num',
            'app_id',
            'app_secret',
            'due_time',
            'try_time:datetime',
            'pay_time:datetime',
            'pay_total',
            'status',
            'vip_level',
            'create_time',
            'enable',
        ],
    ]) ?>

</div>
