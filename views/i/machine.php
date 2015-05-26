<?php
use yii\helpers\Url;
$this->title = '我的机器';
?>
<style>
    body{ background-color: #ffffff !important;}
</style>
<?php if($model):?>
    <div class="h-list"><ul class="m-machine">
        <?php foreach($model as $row):?>
            <li>
                <div class="h-top-left">编号:<?=$row['serial_id']?></div>
                <a href="<?=Url::toRoute(['rent/detail','id'=>$row['id'],'from'=>'machine'])?>">
                    <div class="li-cover">
                        <img class="li-cover-img" src="<?=$row['cover']?>"/>
                    </div>
                    <p class="li-row li-name"><?=$row['brand'],' &nbsp; ',$row['type']?></p>
                    <div class="li-row li-rent">
                        <span class="li-label">到期 : </span>
                        <span class="li-time"><?=date('Y年m月d',$row['due_time'])?></span>
                    </div>
                    <div class="li-row li-rent">
                        <span class="li-label">维修次数 : </span>
                        <span class="li-time"><?=$row['maintain_time']?></span>
                    </div>
                    <div class="li-row li-rent">
                        <span class="li-label">月租 : </span>
                        <span class="li-time">¥ <?= $row['monthly_rent']? :$row['o_monthly_rent']?>元</span>
                    </div>
                </a>
                <?php if($row['status']==1):?>
                    <a href="javascript:void(0);" class="m-apply">
                        租借申请中...
                    </a>
                <?php else:?>
                    <div>
                        <a href="<?=Url::toRoute(['s/apply','id'=>$row['wx_id'],'mid'=>$row['id']])?>" class="m-apply-50 h-b-r">
                            维修申请
                        </a>
                        <a href="<?=Url::toRoute(['i/service','mid'=>$row['id']])?>" class="m-apply-50">维修记录</a>
                    </div>


                <?php endif;?>
            </li>
        <?php endforeach;?>
        <li style="clear:both; display: none;"></li>
    </ul></div>

    <p style="height: 50px;">&nbsp;</p>
    <div style="clear:both; display: none;"></div>

    <a class="h-fixed-bottom" href="<?=Url::toRoute(['rent/list','id'=>$id])?>">
        租借机器
    </a>

 <?php else:?>
 <div class="h-hint">亲，您还没有机器，赶快去租借一台吧</div>
<a class="h-button" href="<?= Url::toRoute(['/rent/list','id'=>$id])?>">租借机器</a>

<?php endif;?>