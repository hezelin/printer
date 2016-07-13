<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '任务记录';
?>

<header class="aui-bar aui-bar-nav aui-bar-color">新维修任务 <span class="aui-badge aui-badge-warning"><?=$count?></span> </header>

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
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 没有数据</div>
    <?php endif;?>
</div>