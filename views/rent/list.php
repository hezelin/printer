<?php
use yii\helpers\Url;
    $this->title = '机器列表';
?>

<div class="h-list">
    <?php if($model && count($model)>0):?>
        <ul>
        <?php foreach($model as $row):?>
            <li>
                <a href="<?=Url::toRoute(['rent/detail','id'=>$row['id']])?>">
                    <div class="li-cover">
                        <img class="li-cover-img" src="<?=$row['cover']?>"/>
                    </div>
                    <p class="li-row li-name"><?=$row['brand'],$row['type']?></p>
                    <div class="li-row li-rent">
                        <span class="li-label">月租</span>
                        <em class="li-yan">¥</em>
                        <span class="li-price"><?=$row['monthly_rent']?></span>
                    </div>
                    <p class="li-row li-func"><?=$row['function']?><?=$row['function']?></p>
                </a>
            </li>
        <?php endforeach;?>
            <li style="clear:both; display: none;"></li>
        </ul>
    <?php else:?>
        <p class="blank-info">没有机器可租借</p>
    <?php endif;?>
</div>