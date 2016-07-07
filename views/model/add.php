<?php
use yii\helpers\Url;
    $this->title = '添加机器模型';
$this->params['breadcrumbs'][] = ['label'=>'机型列表','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'list' => $list,
]) ?>
