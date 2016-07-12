<?php
use yii\helpers\Url;
$this->title = '我的机器';
?>

<div class="aui-content" id="task-list">
    <?php if($model || $project):?>
        <header class="aui-bar aui-bar-nav aui-bar-color">我的机器 <span class="aui-badge aui-badge-warning"><?=count($model)+count($project)?></span> </header>
        <ul class="aui-list-view">
            <?php foreach($project as $row):?>
                <li class="aui-list-view-cell aui-img">
                    <a class="aui-arrow-right" href="<?=url::toRoute(['/user/rent/detail','id'=>$id,'project_id'=>$row['id']])?>">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <h2 class="aui-ellipsis-1 aui-text-info"><?=$row['brand_name'],$row['model']?></h2>
                            <p class="aui-ellipsis-1 aui-text-default">
                                月租 : <span class="aui-red-color">¥<?= $row['lowest_expense']?></span>
                            </p>
                            <span class="aui-box aui-box-color">租借申请中...</span>
                        </div>
                    </a>
                </li>
            <?php endforeach;?>

            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                    <a class="aui-arrow-right" href="<?=url::toRoute(['/user/rent/machine-detail','id'=>$id,'rent_id'=>$row['rent_id'],'from'=>'machine'])?>">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <h2 class="aui-ellipsis-1 aui-text-info"><?=$row['brand_name'],$row['model_name']?></h2>
                            <p class="aui-ellipsis-1 aui-text-default">
                                月租 : <span class="aui-red-color">¥<?= $row['monthly_rent']?></span>
                            </p>
                            <p class="aui-ellipsis-1 aui-text-default">
                                到期 : <span class="aui-color-000"><?=date('Y年m月d',$row['due_time'])?></span>
                            </p>
                            <p class="aui-ellipsis-1 aui-text-default">
                                维修次数 : <span class="aui-color-000"><?=$row['maintain_count']?></span>
                            </p>
                        </div>
                    </a>
                    <p class="aui-ellipsis-1" style="margin-left: 95px;">
                        <a class="aui-box aui-box-color aui-box-sm" href="<?=Url::toRoute(['i/service','id'=>$id,'mid'=>$row['id']])?>">维修记录</a>
                        <a class="aui-box aui-box-sm" href="<?=url::toRoute(['/maintain/fault/apply','id'=>$id,'mid'=>$row['id']])?>">维修申请</a>
                    </p>
                </li>
            <?php endforeach;?>
        </ul>
        <div class="aui-fixed-bottom">
            <a href="<?=url::toRoute(['/user/rent/list','id'=>$id])?>">
                租借机器
            </a>
        </div>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 亲，您还没有机器，赶快去租借一台吧</div>
        <a href="<?= url::toRoute(['/user/rent/list','id'=>$id])?>" class="aui-btn my-btn-block">租借机器</a>
    <?php endif;?>
</div>