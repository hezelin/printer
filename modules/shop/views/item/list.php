<?php
use app\components\PassthroughWidget;
use yii\helpers\Url;
$this->title = '配件列表';
?>

<?php
    echo PassthroughWidget::widget([
        'data'=>$category,
        'startId'=>$startId,
        'action'=>Url::toRoute(['/shop/parts/list','id'=>$id]),
    ]);
?>

<style>
    <?php $this->beginBlock('CSS') ?>
    .item-list{
        display: block;
        padding: 12px 0;
        width: 90%;
        margin: 0 5%;
        border-bottom: 1px #e3e3e3 solid;
        font-size: 16px;
    }
    .item-list img{
        width: 80px;
        height: 60px;
        float: left;
        margin-right: 10px;
    }
    .item-list span h5{
        height: 55px;
        color: #666;
        font-weight: normal;
        font-size: 16px;
    }
    .mtm_p{
        text-align: right;
        color: #999;
        font-size: 14px;
    }
    .mtm_p b{
        float: left;
        color: #b10000;
        font-size: 16px;
    }

    .item-more{
        width: 80%; background-color: #efefef;font-size: 14px;
        /*box-shadow: 1px 1px 2px #cccccc; */
        border: 1px solid #EEEEEE;
        text-align: center; height: 36px; line-height: 36px;
        margin: 15px auto; border-radius: 4px; color: #666666;
    }
    .item-more-end{
        background-color: #FFFFFF;color: #cccccc;
        border: 0px;
    }
    <?php $this->endBlock() ?>
</style>
<?php
$this->registerCss($this->blocks['CSS']);
?>

<div id="item-list-wrap">
    <?php if($model):?>
        <?php foreach($model as $row):?>
   <a class="item-list" href="<?=Url::toRoute(['detail','id'=>$row['wx_id'],'item_id'=>$row['id']])?>">
       <img src="<?=$row['cover']?>">
       <span>
           <h5><?=$row['name']?></h5>
           <p class="mtm_p"><b>￥<?=$row['price']?></b><?=$row['category']?></p>
       </span>
   </a>
       <?php endforeach;?>
    <?php endif;?>
</div>

<?php if(count($model)<10):?>
    <div class="item-more item-more-end">
        没有数据了
    </div>
<?php else:?>
<div id="item-more" class="item-more item-more-end">
    查看更多
</div>
<?php endif;?>

<style>
    var startId = <?=$startId?>;
</style>