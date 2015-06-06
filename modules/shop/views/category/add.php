<?php
use yii\helpers\Url;
    $this->title = '类目添加';
?>
<div >
    <ul class="nav nav-tabs" >
        <li class="active"><a href="javascript:void(0)" >添加</a></li>
        <li><a href="<?=Url::toRoute(['list','url'=>Yii::$app->request->get('url')])?>" >列表</a></li>
    </ul>
</div>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
