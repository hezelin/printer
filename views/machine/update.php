<?php

$this->title = '机器资料修改';
$this->params['breadcrumbs'][] = ['label'=>'机器列表','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [ 'model' => $model ]) ?>

