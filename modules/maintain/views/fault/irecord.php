<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '机器维修记录';
?>

<header class="aui-bar aui-bar-nav aui-bar-color">维修次数 <span class="aui-badge aui-badge-warning"><?=count($model)?></span> </header>

<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                    <a class="aui-arrow-right" href="<?=url::toRoute(['/maintain/fault/detail','id'=>$id,'fault_id'=>$row['id']])?>">

                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <h2 class="aui-ellipsis-1 aui-text-primary">故障：<?=ConfigBase::getFaultStatus($row['type'])?></h2>
                            <p class="aui-ellipsis-2">描述：<?=$row['desc']?></p>
                            <span class="aui-box aui-box-color"><?=ConfigBase::getFixStatus($row['status'])?></span>
                        </div>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 没有数据</div>
    <?php endif;?>
</div>