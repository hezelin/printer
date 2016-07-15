<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '任务列表';

//$this->registerJsFile('/js/aui/api.js');
//$this->registerJsFile('/js/aui/aui-tap.js');
use app\assets\HomeAsset;
HomeAsset::register($this);
?>

<div class="aui-tab aui-color">
    <ul class="aui-tab-nav">
        <li <?=Yii::$app->request->get('type')? '':'class="active"'?>><a href="<?=Url::toRoute(['/maintain/task/list','id'=>$id])?>">维修中</a></li>
        <li <?=Yii::$app->request->get('type')=='evaluate'? 'class="active"':''?>><a href="<?=Url::toRoute(['/maintain/task/list','id'=>$id,'type'=>'evaluate'])?>">待评价</a></li>
        <li <?=Yii::$app->request->get('type')=='history'? 'class="active"':''?>><a href="<?=Url::toRoute(['/maintain/task/list','id'=>$id,'type'=>'history'])?>">历史记录</a></li>
    </ul>
</div>

<div class="aui-content" id="task-list">
<?php if( is_array($model) && $model ):?>
<ul class="aui-list-view">
    <?php foreach($model as $row):?>
        <li class="aui-list-view-cell aui-img">
            <a href="<?=Url::toRoute(['/maintain/task/detail','id'=>$row['id']])?>">

                <img class="aui-img-object aui-pull-left" src="<?=$row['fault_cover']?>">
                <div class="aui-img-body">
                    <h2 class="aui-ellipsis-1">故障：<?=ConfigBase::getFaultStatus($row['fault_type'])?></h2>
                    <p class="aui-ellipsis-2">描述：<?=$row['desc']?></p>
                    <p class="aui-ellipsis-1"><span class="iconfont icon-yonghu aui-color"></span>
                        <?php
                        if($row['name'])
                        {
                            echo $row['name'],' ',$row['phone'];
                            echo $row['name'],' ',$row['phone'];
                            echo $row['name'],' ',$row['phone'];
                        }
                        else
                            echo '电话维修用户';
                        ?>
                    </p>
                    <p class="aui-ellipsis-1"><span class="iconfont icon-dizhi aui-color"></span> <?=$row['address']? :'未设置'?></p>
                    <p class="aui-btn aui-btn-color aui-btn-sm"><?php
                        if( Yii::$app->request->get('type')=='history' )
                            echo date('Y-m-d',$row['add_time']);
                        else
                            echo ConfigBase::getFixStatus($row['status']);
                        ?></p>
                </div>
            </a>
        </li>
    <?php endforeach;?>
</ul>
<?php else:?>
    <div class="aui-padded-10 aui-text-center" style="font-size: 14px; margin-top:160px;">目前任何记录</div>
<?php endif;?>
</div>