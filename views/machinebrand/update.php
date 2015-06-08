<?php
use yii\helpers\Url;
$this->title = '修改品牌';
?>
    <h4>品牌管理</h4>
<div >
    <ul class="nav nav-tabs" >
        <li class="active"><a href="javascript:void(0)" >修改</a></li>
        <li><a href="<?=Url::toRoute(['list','url'=>Yii::$app->request->get('url')])?>" >列表</a></li>
    </ul>
</div>

<?= $this->render('_form', [
    'model' => $model,
]) ?>