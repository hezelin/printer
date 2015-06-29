<?php
use app\components\PassthroughWidget;
use yii\helpers\Url;
$this->title = '配件列表';
Yii::$app->params['layoutBottomHeight'] = 40;

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
            <div class="item-list">
                <img src="<?=$row['cover']?>">
               <span>
                   <h5>(<?=$row['category']?>)<?=$row['name']?></h5>
                   <p class="mtm_p"><b><?=\app\modules\shop\models\Shop::getParts($row['status'])?></b>
                       <a class="parts-cancel-btn" parts-id="<?=$row['parts_id']?>" href="javascript:void(0)">取消</a></p>
               </span>
            </div>
        <?php endforeach;?>
    <?php else:?>
        <div class="h-hint">没有配件</div>
    <?php endif;?>
</div>

<div class="h-fixed-bottom">
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