<?php
use yii\helpers\Url;
$this->title = '修改租借方案';
$this->params['breadcrumbs'][] = ['label'=>'方案列表','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>