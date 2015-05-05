<?php
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '公众号详情';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info" role="alert">
    1.在公众平台(<span class="red">开发者中心</span>)-><span class="red">服务配置</span>，填写下面的 <span class="red"> Token(令牌)、URL(服务器地址)</span><br/>
    2.在公众平台(<span class="red">开发者中心</span>)->网页服务->网页账号->网页授权获取用户基本信息设置授权回调页面域名为<span class="red"> <?=$host?></span><br/>
    3.在公众平台(<span class="red">公众号设置</span>)->功能设置->JS接口安全域名，填写<span class="red"><?=substr($host,7)?></span>
</div>

<?php

function status($obj){
    if($obj->due_time < time())
        return '已到期';
    return ConfigBase::getWxStatus($obj->status);
}

echo DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-striped detail-view'],
    'attributes' => [
        'name',
        'wx_num',
        'app_id',
        'app_secret',
        [
            'label' => 'Token(令牌)',
            'value' => Html::tag('span',$wxToken,['class'=>'red']),
            'format' => 'html',
        ],
        [
            'label' => 'URL(服务器地址)',
            'value' => Html::tag('span',Url::to(['app/view','id'=>$model->id],true),['class'=>'red']),
            'format' => 'html',

        ],
        [
            'label' => '回调域名',
            'value' => Html::tag('span',$host,['class'=>'red']),
            'format' => 'html',

        ],
        [
            'attribute'=>'due_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'attribute'=>'status',
            'value' => status($model),

        ],
        [
            'attribute' => 'vip_level',
            'value' => ConfigBase::getVip($model->vip_level),
        ],
        [
            'attribute'=>'create_time',
            'format' => ['date', 'php:Y-m-d'],
        ],
    ],
]);
?>