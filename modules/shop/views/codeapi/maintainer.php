<?php
use yii\helpers\Url;
$this->title = '扫描配件';
?>
<div class="h-center-wrap">
    <a class="h-link" href="<?=Url::toRoute(['/shop/parts/log','un'=>$un,'id'=>$id,'item_id'=>$item_id])?>">
        查看记录
    </a>
    <?php if( $part['status'] == 10 ):?>
        <a class="h-link" href="<?=Url::toRoute(['/shop/parts/unbing','id'=>$id,'un'=>$un,'item_id'=>$item_id,'machine_id'=>$part['machine_id'],'openid'=>$openid,'part_id'=>$part['id'] ])?>">
            解除绑定
        </a>
    <?php else:?>
    <a class="h-link" href="<?=Url::toRoute(['/shop/parts/select','id'=>$id,'un'=>$un,'item_id'=>$item_id,'openid'=>$openid])?>">
        绑定机器
    </a>
    <?php endif;?>
    <a class="h-link" href="<?=Url::toRoute(['/shop/parts/remark','id'=>$id,'un'=>$un,'item_id'=>$item_id,'openid'=>$openid])?>">
        备注消息
    </a>
</div>