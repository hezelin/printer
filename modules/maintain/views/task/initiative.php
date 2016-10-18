<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '任务记录';
use app\assets\HomeAsset;
HomeAsset::register($this);
?>

<style>
    .aui-pull-center{
        /* Safari and Chrome */
        position:absolute;top:50%;left:50%; transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);
    }
    .aui-extend-circle{
        float:left;height:20px; line-height: 19px; padding:0 6px; margin-top:13px; margin-left:5px;
    }
</style>

<header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center; aui-col-xs-12">
    <div class="aui-pull-center">
        <span class="aui-pull-left">新维修任务</span>
        <span class="aui-badge aui-badge-danger aui-extend-circle"><?=$count;?></span>
    </div>
</header>

<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                    <a class="aui-arrow-right" href="<?=Url::toRoute(['/maintain/task/detail','id'=>$row['id']])?>">

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
                            <p class="aui-ellipsis-1"><span class="iconfont icon-shijian aui-color"></span> <?=date('m月d日 H:i',$row['add_time'])?></p>
                        </div>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="aui-padded-10 aui-text-center" style="font-size: 14px; margin-top:160px;">目前没有维修任务</div>
    <?php endif;?>
</div>