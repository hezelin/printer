<?php
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '公众号详情';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info" role="alert">
    1、在公众平台(<span class="red">开发</span>)-><span class="red">基本配置</span>，填写下面的 <span class="red"> Token(令牌)、URL(服务器地址)</span> 并且点击 &nbsp;&nbsp;&nbsp; <span class="red"> 启用 </span>  &nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/images/one1.png?t=1" class="btn btn-sm btn-info">图片示例</a><br/>
    2、在公众平台(<span class="red">设置</span>)->公众号设置->功能设置->设置授权域名为 &nbsp; <span class="red"><?=$host?></span> &nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/images/one2.png?t=1" class="btn btn-sm btn-info">图片示例</a><br/>
    3、以上步骤完成之后，在 &nbsp;&nbsp;&nbsp; <a href="<?=Url::toRoute(['/weixin/index'])?>" class="btn btn-sm btn-success">我的公众号</a> &nbsp;&nbsp;&nbsp; 列表页面开通服务。
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
            'label' => 'URL(服务器地址)',
            'value' => Html::tag('span',Url::to(['app/view','id'=>$model->id],true),['class'=>'red']),
            'format' => 'html',

        ],
        [
            'label' => 'Token(令牌)',
            'value' => Html::tag('span',$wxToken,['class'=>'red']),
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