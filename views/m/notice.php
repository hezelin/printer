<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '通知记录';
?>

<div class="h-box">
    <?php if($model && $count >0):?>
        <p class="h-header">新通知 <?=$count?> 个</p>
        <ul>
            <?php foreach($model as $row):?>
                <li>
                    <p style="font-size:16px; color:#666; padding: 0 10px;"><?=$row['text']?></p>
                    <p style="font-size: 14px; color: #ccc; text-align: right; padding: 10px 0;"><?=date('Y-m-d H:i',$row['add_time'])?></p>
                </li>
            <?php endforeach;?>
            <li style="clear:both; display: none;"></li>
        </ul>
    <?php else:?>
        <p class="blank-info">没有最新通知</p>
    <?php endif;?>
</div>