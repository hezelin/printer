<?php
use yii\helpers\Url;
$this->title = '机器列表';
?>


<div class="aui-content" id="task-list">
    <?php if($model):?>
        <header class="aui-bar aui-bar-nav aui-bar-color">租赁方案列表 <span class="aui-badge aui-badge-warning"><?=count($model)?></span> </header>

        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                    <a href="<?=Url::toRoute(['rent/detail','id'=>$id,'project_id'=>$row['id']])?>">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <h2 class="aui-ellipsis-1 aui-text-info"><?=$row['name'],$row['type']?></h2>
                            <p class="aui-ellipsis-1 aui-text-default">
                                月租 : <span class="aui-red-color">¥<?= $row['lowest_expense']?></span>
                            </p>
                            <p class="aui-ellipsis-2 aui-color-000"><?=$row['function']?></p>
                        </div>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
        <div class="aui-fixed-bottom">
            <a href="<?=Url::toRoute(['rent/list','id'=>$id])?>">
                租借机器
            </a>
        </div>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 亲，您还没有机器，赶快去租借一台吧</div>
        <a href="<?= Url::toRoute(['/rent/list','id'=>$id])?>" class="aui-btn my-btn-block">租借机器</a>
    <?php endif;?>
</div>