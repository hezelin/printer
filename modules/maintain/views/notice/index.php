<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '通知记录';
?>
<header class="aui-bar aui-bar-nav aui-bar-color">新通知 <span class="aui-badge aui-badge-warning"><?=$count?></span> </header>

<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                        <div class="aui-img-body">
                            <p class="aui-ellipsis-2">描述：<?=$row['text']?></p>
                            <p class="aui-pull-right"><span class="iconfont icon-shijian"></span> <?=date('m月d日 H:i',$row['add_time'])?></p>
                        </div>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 没有数据</div>
    <?php endif;?>
</div>