<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblWeixin */

$this->title = 'Create Tbl Weixin';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Weixins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-weixin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
