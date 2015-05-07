<?php
use yii\helpers\Url;
$this->title = '我的机器';
?>
<?php if($model):?>
    <div id="rent-list"><ul class="m-machine">
        <?php foreach($model as $row):?>
            <li>
                <a href="<?=Url::toRoute(['rent/detail','id'=>$row['id'],'from'=>'machine'])?>">
                    <div class="li-cover">
                        <img class="li-cover-img" src="<?=$row['cover']?>"/>
                    </div>
                    <p class="li-row li-name"><?=$row['brand'],' &nbsp; ',$row['type']?></p>
                    <div class="li-row li-rent">
                        <span class="li-label">到期 : </span>
                        <span class="li-time"><?=date('Y-m-d H:i',$row['due_time'])?></span>
                    </div>
                    <div class="li-row li-rent">
                        <span class="li-label">编号 : </span>
                        <span class="li-price"><?=$row['serial_id']?></span>
                    </div>
                    <div class="li-row li-rent">
                        <span class="li-label">月租 : </span>
                        <span class="li-time">¥ <?= $row['monthly_rent']? :$row['o_monthly_rent']?>元</span>
                    </div>
                </a>
                <?php if($row['status']==1):?>
                    <a href="javascript:void(0);" class="m-apply">
                        申请中...
                    </a>
                <?php else:?>
                <a href="<?=Url::toRoute(['i/mapply','id'=>$row['id'],'mid'=>$row['wx_id']])?>" class="m-apply">
                    维修申请
                </a>
                <?php endif;?>
            </li>
        <?php endforeach;?>
        <li style="clear:both; display: none;"></li>
    </ul></div>

    <p style="height: 50px;">&nbsp;</p>
    <div style="clear:both; display: none;"></div>

    <a class="de-fiexd-bottom" href="<?=Url::toRoute(['rent/list','id'=>$id])?>">
        租借机器
    </a>

 <?php else:?>
 <div class="hint">亲，您还没有机器，赶快去租借一台吧</div>
<a class="button" href="<?= Url::toRoute(['/rent/list','id'=>$id])?>">租借机器</a>

<?php endif;?>