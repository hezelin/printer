<?php
use yii\helpers\Url;
$this->title = '我的机器';
Yii::$app->params['layoutBottomHeight'] = 40;

?>
<?php if($model || $project):?>
    <div class="h-list"><ul class="m-machine">
        <?php foreach($project as $row):?>
            <li>
                <a href="<?=Url::toRoute(['rent/detail','id'=>$id,'project_id'=>$row['id']])?>">
                    <div class="li-cover">
                        <img class="li-cover-img" src="<?=$row['cover']?>"/>
                    </div>
                    <p class="li-row li-name"><?=$row['name'],$row['type']?></p>
                    <div class="li-row li-rent">
                        <span class="li-label">月租</span>
                        <em class="li-yan"> ¥</em>
                        <span class="li-price"><?=$row['lowest_expense']?></span>
                    </div>
                    <p class="li-row li-func"><?=$row['function']?><?=$row['function']?></p>
                </a>
                <a href="javascript:void(0);" class="m-apply">
                        租借申请中...
                    </a>
            </li>
        <?php endforeach;?>
        <?php foreach($model as $row):?>
            <li>
                <a href="<?=Url::toRoute(['rent/machinedetail','id'=>$id,'rent_id'=>$row['rent_id'],'from'=>'machine'])?>">
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
                    <!--<a href="javascript:void(0);" class="m-apply">
                        租借申请中...
                    </a>-->
                <div>
                    <a href="<?=Url::toRoute(['s/apply','id'=>$id,'mid'=>$row['id']])?>" class="m-apply-50 h-b-r">
                        维修申请
                    </a>
                    <a href="<?=Url::toRoute(['i/service','id'=>$id,'mid'=>$row['id']])?>" class="m-apply-50">维修记录</a>
                </div>
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