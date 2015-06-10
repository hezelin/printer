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
                <a href="<?=Url::toRoute(['rent/detail','id'=>$id,'project_id'=>$row['project_id'],'from'=>'machine'])?>">
                    <div class="li-cover">
                        <img class="li-cover-img" src="<?=$row['cover']?>"/>
                    </div>
                    <p class="li-row li-name"><?=$row['type'],'/',$row['series_id']?></p>
                    <div class="li-row li-rent">
                        <span class="li-label">月租 : </span>
                        <em class="li-yan"> ¥</em>
                        <span class="li-price"><?= $row['monthly_rent']?></span>
                    </div>
                    <div class="li-row li-rent">
                        <span class="li-label">到期 : </span>
                        <span class="li-time"><?=date('Y年m月d',$row['due_time'])?></span>
                    </div>
                    <div class="li-row li-rent">
                        <span class="li-label">维修次数 : </span>
                        <span class="li-time"><?=$row['maintain_count']?></span>
                    </div>
                </a>
                <?php if($row['status']==1):?>
                    <a href="javascript:void(0);" class="m-apply">
                        租借申请中...
                    </a>
                <?php else:?>
                    <div>
                        <a href="<?=Url::toRoute(['s/apply','id'=>$id,'mid'=>$row['id']])?>" class="m-apply-50 h-b-r">
                            维修申请
                        </a>
                        <a href="<?=Url::toRoute(['i/service','id'=>$id,'mid'=>$row['id']])?>" class="m-apply-50">维修记录</a>
                    </div>


                <?php endif;?>
            </li>
        <?php endforeach;?>
        <li style="clear:both; display: none;"></li>
    </ul></div>

    <div class="h-fixed-bottom">
        <a href="<?=Url::toRoute(['rent/list','id'=>$id])?>">
            租借机器
        </a>
    </div>


 <?php else:?>
 <div class="h-hint">亲，您还没有机器，赶快去租借一台吧</div>
<a class="h-button" href="<?= Url::toRoute(['/rent/list','id'=>$id])?>">租借机器</a>

<?php endif;?>