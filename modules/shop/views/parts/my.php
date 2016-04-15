<?php
use yii\helpers\Url;
$this->title = '配件列表';
?>

<header class="aui-bar aui-bar-nav aui-bar-color">我的配件 <span class="aui-badge aui-badge-warning"><?=count($model)?></span> </header>

<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                    <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                    <div class="aui-img-body">
                        <h2 class="aui-ellipsis-1">(<?=$row['category']?>)</h2>
                        <p class="aui-ellipsis-2"><?=$row['name']?></p>
                        <p class="aui-ellipsis-1">
                            <span class="aui-box aui-box-color"><?=\app\modules\shop\models\Shop::getParts($row['status'])?></span>
                            <span class="parts-cancel-btn aui-box" parts-id="<?=$row['parts_id']?>" href="javascript:void(0)">取消</span>
                        </p>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 没有数据</div>
    <?php endif;?>
</div>


<div class="aui-fixed-bottom">
    <a id="process-btn" href="<?=Url::toRoute(['/shop/parts/list','id'=>$id])?>">
        继续申请配件
    </a>
</div>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var hasClick = 0;
    $(function(){
        $('.parts-cancel-btn').click(function(){
            if(hasClick == 1) return false;
            var $this = $(this).closest('.item-list');
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