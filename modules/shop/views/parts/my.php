<?php
use yii\helpers\Url;
$this->title = '配件列表';
use app\assets\HomeAsset;
HomeAsset::register($this);
?>
<style>
    .aui-pull-center{
        /* Safari and Chrome */
        position:absolute;top:50%;left:50%; transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);
    }
    .aui-extend-circle{
        float:left;height:20px; line-height: 19px; padding:0 6px; margin-top:13px; margin-left:5px;
    }
</style>
<header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center; aui-col-xs-12">
    <div class="aui-pull-center">
        <span class="aui-pull-left">我的配件</span>
        <span class="aui-badge aui-badge-danger aui-extend-circle" style=""><?=count($model)?></span>
    </div>
</header>

<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img" style="padding:0;">
                    <div class="aui-border-b" style="overflow:hidden;padding:12px 15px;">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <h2 class="aui-ellipsis-1">(<?=$row['category']?>)</h2>
                            <p class="aui-ellipsis-2"><?=$row['name']?></p>
                            <p class="aui-ellipsis-1">
                                <span class="aui-box aui-box-color aui-bg-default"  style="border:0; color:#333; padding:2px 10px;"><?=\app\modules\shop\models\Shop::getParts($row['status'])?></span>
                                <span class="parts-cancel-btn aui-box aui-bg-danger" style="color:#fff;border:0; padding:2px 17px;" parts-id="<?=$row['parts_id']?>" href="javascript:void(0)">取消</span>
                            </p>
                        </div>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="aui-padded-10 aui-text-center" style="font-size: 14px; margin-top:160px;">目前没有任何配件</div>
    <?php endif;?>
</div>


<div class="aui-fixed-bottom aui-border-t" style="border:0;height:46px; line-height: 46px; background: #fff;">
    <a id="process-btn" href="<?=Url::toRoute(['/shop/parts/list','id'=>$id])?>">
        <span class="aui-iconfont aui-icon-cart"></span>
        <span class="aui-inline-block" style="margin-left:5px; font-size:14px;">申请配件</span>
    </a>
</div>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var hasClick = 0;
    $(function(){
        $('.parts-cancel-btn').click(function(){
            if(hasClick == 1) return false;
            var $this = $(this).closest('.aui-list-view-cell');
            var parts_id = $(this).attr('parts-id');
            $.post(
                '<?=Url::toRoute(['cancel','id'=>$id])?>&parts_id='+parts_id,
                function(resp){
                    if(resp.status == 1)
                        $this.remove();
                    else alert( resp.msg );
                    hasClick = 0;
                },'json'
            );
        });
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

?>