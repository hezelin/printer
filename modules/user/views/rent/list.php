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
                    <a href="<?=url::toRoute(['/user/rent/detail','id'=>$id,'project_id'=>$row['id']])?>">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body aui-arrow-right">
                            <h2 class="aui-ellipsis-1 aui-text-info"><?=$row['brand_name'],$row['model_name']?></h2>
                            <p class="aui-ellipsis-1 aui-text-default">
                                月租 : <span class="aui-red-color">¥<?= $row['lowest_expense']?></span>
                            </p>
                            <p class="aui-ellipsis-1 aui-color-000">
                                黑白: <span class="aui-red-color"><?= \app\models\config\Tool::schemePrice($row['black_white'])?></span>
                            </p>
                            <?php if($row['colours']):?>
                            <p class="aui-ellipsis-1 aui-color-000">
                                彩色: <span class="aui-red-color"><?= \app\models\config\Tool::schemePrice($row['colours'])?></span>
                            </p>
                            <?php endif;?>
                        </div>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 还没有租赁方案</div>
    <?php endif;?>
</div>