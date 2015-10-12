<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '任务列表';
Yii::$app->params['layoutBottomHeight'] = 40;

?>

<div class="h-list">
    <?php if($model):?>
        <ul>
            <?php if(isset($model['process'])):?>
                <li class="li-title">处理中维修单</li>
                <?php foreach($model['process'] as $row):?>
                    <li>
                        <a href="<?=Url::toRoute(['m/taskdetail','id'=>$row['id']])?>">
                            <div class="li-cover">
                                <img class="li-cover-img" src="<?=$row['fault_cover']?>"/>
                            </div>
                            <p class="li-row li-name">故障：<?=ConfigBase::getFaultStatus($row['fault_type'])?></p>
                            <p class="li-row-small">描述：<?=$row['desc']?></p>
                            <p class="li-row-small"><?=$row['name'],',',$row['phone']?></p>
                            <p class="li-row-small"><?=$row['address']?></p>
                            <p class="li-row-small">状态：<span style="color: red"><?=ConfigBase::getFixStatus($row['status'])?></span></p>
                        </a>
                    </li>
                <?php endforeach;?>
            <?php endif;?>
            <?php if(isset($model['evaluate'])):?>
                <li class="li-title">待评价维修单</li>
                <?php foreach($model['evaluate'] as $row):?>
                    <li>
                        <a href="<?=Url::toRoute(['m/taskdetail','id'=>$row['id']])?>">
                            <div class="li-cover">
                                <img class="li-cover-img" src="<?=$row['fault_cover']?>"/>
                            </div>
                            <p class="li-row li-name">故障：<?=ConfigBase::getFaultStatus($row['fault_type'])?></p>
                            <p class="li-row-small">描述：<?=$row['desc']?></p>
                            <p class="li-row-small"><?=$row['name'],',',$row['phone']?></p>
                            <p class="li-row-small"><?=$row['address']?></p>
                            <p class="li-row-small">状态：<span style="color: red"><?=ConfigBase::getFixStatus($row['status'])?></span></p>
                        </a>
                    </li>
                <?php endforeach;?>
            <?php endif;?>
<!--            <li style="clear:both; display: none;"></li>-->
        </ul>
    <?php else:?>
        <p class="blank-info">没有新维修任务</p>
    <?php endif;?>
</div>

<a class="h-fixed-bottom" href="<?=Url::toRoute(['m/record','id'=>$id])?>">
    历史完成维修
</a>