<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '任务记录';
?>

<div class="h-list">
    <?php if($model && count($model)>0):?>
        <ul>
            <?php foreach($model as $row):?>
                <li>
                    <a href="<?=Url::toRoute(['s/detail','id'=>$row['id']])?>">
                        <div class="li-cover">
                            <img class="li-cover-img" src="<?=$row['cover']?>"/>
                        </div>
                        <p class="li-row li-name">故障：<?=ConfigBase::getFaultStatus($row['type'])?></p>
                        <p class="li-row">描述：<?=$row['desc']?></p>
                        <p class="li-rowl">状态：<span style="color: red"><?=ConfigBase::getFixStatus($row['status'])?></span></p>
                    </a>
                </li>
            <?php endforeach;?>
            <li style="clear:both; display: none;"></li>
        </ul>
    <?php else:?>
        <p class="blank-info">没有维修记录</p>
    <?php endif;?>
</div>