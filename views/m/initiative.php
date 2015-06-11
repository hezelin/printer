<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '任务记录';
?>

<div class="h-list">
    <?php if($model && $count >0):?>
        <p class="h-header">新维修任务 <?=$count?> 个</p>
        <ul>
            <?php foreach($model as $row):?>
                <li>
                    <a href="<?=Url::toRoute(['m/taskdetail','id'=>$row['id']])?>">
                        <div class="li-cover">
                            <img class="li-cover-img" src="<?=$row['fault_cover']?>"/>
                        </div>
                        <p class="li-row li-name">故障：<?=ConfigBase::getFaultStatus($row['fault_type'])?></p>
                        <p class="li-row-small">描述：<?=$row['desc']?></p>
                        <p class="li-row-small"><?=$row['name'],',',$row['phone']?></p>
                        <p class="li-row-small"><?=$row['address']?></p>
                        <p class="li-row-small">发起时间：<?=date('m月d日 H:i',$row['add_time'])?></p>
                    </a>
                </li>
            <?php endforeach;?>
            <li style="clear:both; display: none;"></li>
        </ul>
    <?php else:?>
        <p class="blank-info">没有最新维修</p>
    <?php endif;?>
</div>