<?php
use yii\helpers\Url;
    $this->title = '工作任务'
?>
<style>
    .mod_navbar {
        border-bottom: 1px solid #e1e1e1;
        -webkit-box-shadow: 1px 0 3px #eee,inset 0 0 3px #fff;
        box-shadow: 1px 0 3px #eee,inset 0 0 3px #fff;
        padding: 0 15px;
        position: absolute;
        top: 0px;
        left:0;
        width: 100%;
        background-color: #f9f9f7;
        z-index: 3;
        height: 60px;

    }
    .no-padding{
        padding: 0;
        padding-left: 15px;
        padding-bottom: 15px;
    }
    .row-box{
        box-shadow: 0 0 3px #eee,inset 0 0 3px #fff;
        border: solid 1px #e1e1e1;
        padding: 15px 15px 30px 15px;
        background-color: #fbfbfb;
        position: relative;
    }
    .row-box .box-panel-body{
        height: 180px;
        overflow: hidden;
    }
    .row-box .box-panel-header {
        border-bottom: 1px solid #e1e1e1;
        margin-bottom: 15px;
        display: block;
    }
    .row-box .box-panel-header h4 {
        color: #646b75;
        font-size: 18px;
        margin-bottom: 15px;
        margin-top: 0;
    }
    h4{ margin: 0}
    .empty-panel {
        margin: 0 auto;
        text-align: center;
        text-shadow: 0 1px 0 #fff,0 -1px 0 #f2f2ea;
        color: #cccccc;
        font-size: 18px;
        height: 200px;
        line-height: 200px;
    }
    .icon{ margin-right: 5px;}
    ul,li{
        list-style: none; margin: 0; padding: 0;
    }
    .box-list-li{
        height: 30px; line-height: 30px;
    }
    .m-name{  color: #333333; width: 33%; display: inline-block;}
    .m-tips{ color: #999999; width: 33%; display: inline-block;}
    
    .num-alert{color: #f7af49; font-size: 16px;}

    .fault-list-ul{
        overflow: hidden;
    }
    .fault-list-li{
        height: 60px;
        padding-top:5px;
        border-bottom: 1px dotted #cccccc;
    }
    .fault-data{ width: 40%; float: left; position: relative;}
    .fault-btn{ width: 20%; float: right; text-align: right; margin-top: 8px;}
    .fault-data span { color:#AAAAAA; height: 20px; line-height: 20px; overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; width: 100%;}
    .fault-data h4{ color: #333333; height: 30px; line-height: 30px;overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;}
    .cover-img{
        width: 50px;
        height: 50px;
        margin-right: 10px;
        float: left;
    }
    .fault-time{
        color: #AAAAAA; font-size: 12px; font-weight: 500;
    }
    a{ cursor: pointer;}
    .high-show{ color: #f7af49; font-weight: 400;}


    .order-list-li{ width: 100%;  float: left; display: block; overflow: hidden; border-bottom: 1px dotted #cccccc}
    .order-list-m{ width: 25%;float: left;}
    .order-list{ width: 30%;float: left;}
    .order-address{ width: 30%;float: left;}
    .order-btn{width: 15%; float: left; margin-top: 20px;}

    .order-list p{ height: 20px; line-height: 20px; margin: 0; padding:0;}
    .order-list-m li{
        height: 50px; width: 180px; border-bottom: 1px solid #cccccc; padding: 7px 0; margin-bottom: 3px;
    }
    .order-list-m img{
        height: 40px; width: 40px; margin-right: 10px;
        float: left;
    }
    .order-list-m b{ color: #b10000; float: right; font-weight: 500;}
    .order-list li{
        height: 24px;
        line-height: 24px;
    }
    .order-name{ overflow: hidden; width: 130px; height: 20px;  float: left;}
    .order-error{
        display: inline-block;
        color: #ff0000;
    }
    .order-address li{
        line-height: 24px;
    }
    .order-remark{
        color: #1e90ff;
    }


    .part-list{ width: 25%;float: left;}
    .part-list li{
        height: 50px; width: 180px; border-bottom: 1px solid #cccccc; padding: 7px 0; margin-bottom: 3px;
    }
    .part-data{ width: 30%; float: left; position: relative;}
    .part-btn{ width: 15%; float: right; text-align: right; margin-top: 8px;}
    .part-data span { color:#AAAAAA; height: 20px; line-height: 20px; overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; width: 100%;}
    .part-data h4{ color: #333333; height: 30px; line-height: 30px;overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;}
    .part-li{
        border-bottom:none !important;
    }

    .list-more{ position: absolute; bottom: 0px; left: 0; text-align: center; height: 28px;
        width: 100%;  cursor: pointer; color: #999999;
    }
    .list-all .box-panel-body{
        height: auto;
    }


</style>
<div class="row">
    <div class="mod_navbar">
        <div class="title"> <h3><i class="icon glyphicon glyphicon-tasks"></i>工作任务</h3> </div>
    </div>
</div>
<div class="row">
    <p style="height: 40px;">&nbsp;</p>
    <div class="col-md-9 no-padding">
        <div class="row-box">
            <div class="box-panel-header">
                <h4><i class="icon glyphicon glyphicon-wrench"></i>待分配维修</h4>
            </div>
            <div class="box-panel-body">
                <?php if($data['fault']):?>
                    <ul class="fault-list-ul">
                        <?php foreach($data['fault'] as $d):?>
                            <li class="fault-list-li">
                                <div class="fault-data">
                                    <a href="<?=$d['cover']?>" class="fancybox" rel="group"><img class="cover-img" src="<?=$d['cover']?>" /></a>
                                    <h4><?=\app\models\ConfigBase::getFaultStatus($d['type'])?>,
                                        <b class="fault-time"><?=date('m月d H:i',$d['add_time'])?></b>
                                    </h4>
                                    <span><?=$d['desc']?></span>
                                </div>
                                <div class="fault-data">
                                    <h4><?=$d['brand'],$d['model'],' , ',$d['name']?></h4>
                                    <span><?=$d['phone'],',',$d['address']?></span>
                                </div>
                                <div class="fault-btn">
                                    <button type="button" class="btn btn-info btn-sm">分配</button>
                                    <button type="button" class="btn btn-danger btn-sm">删除</button>
                                </div>
                            </li>
                        <?php endforeach;?>
                    </ul>
                <?php else:?>
                    <div class="empty-panel">没有工作任务</div>
                <?php endif;?>
            </div>
            <?php if(count($data['fault'])>3):?>
            <div class="list-more" title="展开">
                <i class="glyphicon glyphicon-menu-down"></i> 展开
            </div>
            <?php endif;?>
        </div>
    </div>
    <div class="col-md-3 no-padding">
        <div class="row-box">
            <div class="box-panel-header">
                <h4><i class="icon glyphicon glyphicon-user"></i>维修员状态</h4>
            </div>
            <div class="box-panel-body">
                <?php if($data['maintainer']):?>
                <ul class="box-list-ul">
                    <?php foreach($data['maintainer'] as $d):?>
                    <li class="box-list-li">
                        <span class="m-name"><?=$d['name']?></span><span class="m-tips">待修机器</span><span class="num-alert"><?=$d['wait_repair_count']?></span>
                    </li>
                    <?php endforeach;?>
                </ul>
                <?php else:?>
                <div class="empty-panel">没有工作任务</div>
                <?php endif;?>
            </div>
            <?php if(count($data['maintainer'])>6):?>
                <div class="list-more" title="展开">
                    <i class="glyphicon glyphicon-menu-down"></i> 展开
                </div>
            <?php endif;?>
        </div>
    </div>

    <div class="col-md-12 no-padding">
        <div class="row-box">
            <div class="box-panel-header">
                <h4><i class="icon glyphicon glyphicon-shopping-cart"></i>订单处理</h4>
            </div>
            <div class="box-panel-body">
                <?php if($data['order']):?>
                    <ul class="order-list-ul">
                    <?php foreach($data['order'] as $d):?>
                    <li class="order-list-li">
                        <ul class="order-list-m">
                            <?php foreach($d['order_data'] as $i):?>
                            <li>
                                <a href="/shop/backend/view?id=<?=$i['item_id']?>">
                                    <img class="order-cover" src="<?=$i['cover']?>"><span class="order-name"><?=$i['name']?></span>
                                    <p class="order-num-price"><?=$i['item_nums']?> 件 <b>¥<?=number_format($i['item_nums']*$i['price'],2,'.','')?></b></p>
                                </a>
                            </li>
                            <?php endforeach;?>
                        </ul>
                        <div class="order-list">
                           <p>支付金额: <span style="color:#b10000;font-weight: 600"> ¥<?=$d['total_price']?></span></p>
                           <p>积分支付: <span><?=$d['pay_score']?></span></p>
                           <p>其中运费: <span><?=$d['freight']?></span></p>
                           <p>方式: <span><?=\app\modules\shop\models\Shop::getPayStatus($d['pay_status'])?></span></p>
                        </div>
                        <ul class="order-address">
                            <li><?=$d['name'],$d['phone']?></li>
                            <li><?=$d['city'],$d['address']?></li>
                            <li class="order-remark"><?=$d['remark']?></li>
                        </ul>
                        <div class="order-btn">
                            <?php if($d['order_status'] == 1):?>
                                <button type="button" class="btn btn-info btn-sm">发货</button>
                            <?php else:?>
                            <button type="button" class="btn btn-info btn-sm">通过</button>
                            <button type="button" class="btn btn-danger btn-sm">不通过</button>
                            <?php endif;?>
                        </div>
                    </li>
                    <?php endforeach;?>
                    </ul>
                <?php else:?>
                    <div class="empty-panel">没有工作任务</div>
                <?php endif;?>
            </div>
            <div class="list-more" title="展开">
                    <i class="glyphicon glyphicon-menu-down"></i> 展开
                </div>
        </div>
    </div>
    <div class="col-md-12 no-padding">
        <div class="row-box">
            <div class="box-panel-header">
                <h4><i class="icon glyphicon glyphicon-object-align-vertical"></i>配件处理</h4>
            </div>
            <div class="box-panel-body">
                <?php if($data['part']):?>
                    <ul class="order-list-ul">
                        <?php foreach($data['part'] as $d):?>
                            <li class="order-list-li">
                                <ul class="order-list-m">
                                    <li class="part-li">
                                        <a href="/shop/backend/view?id=<?=$d['item_id']?>">
                                            <img class="order-cover" src="<?=$d['cover']?>"><span class="order-name"><?=$d['name']?></span>
                                            <p class="order-num-price">销售价格<b>¥<?=number_format($d['price'])?></b></p>
                                        </a>
                                    </li>
                                </ul>
                                <div class="part-data">
                                    <?php if(isset($d['type'])):?>
                                    <a href="<?=$d['fault_cover']?>" class="fancybox" rel="group">
                                        <img class="cover-img" src="<?=$d['fault_cover']?>" />
                                    </a>
                                    <h4><?=\app\models\ConfigBase::getFaultStatus($d['type'])?></h4>
                                    <span><?=$d['desc']?></span>
                                    <?php else:?>
                                        携带申请
                                    <?php endif;?>
                                </div>
                                <div class="part-data">
                                    <h4><?=$d['nickname']?></h4>
                                    <span><?=$d['phone']?></span>
                                </div>
                                <div class="part-btn">
                                    <?=\app\modules\shop\models\Shop::getParts($d['status'])?>
                                    <?php
                                        switch($d['status']){
                                            case 1:
                                                echo '<button type="button" class="btn btn-info btn-sm">发货</button>';
                                                break;
                                            case 2:
                                            case 3:
                                            case 4:
                                                echo '<button type="button" class="btn btn-info btn-sm">绑定</button>';
                                                break;
                                            case 11:
                                                echo '<button type="button" class="btn btn-info btn-sm">回收</button>';
                                                break;
                                        }
                                    ?>

                                </div>
                            </li>
                        <?php endforeach;?>
                    </ul>
                <?php else:?>
                    <div class="empty-panel">没有工作任务</div>
                <?php endif;?>
            </div>
            <?php if(count($data['part'])>3):?>
                <div class="list-more" title="展开">
                    <i class="glyphicon glyphicon-menu-down"></i> 展开
                </div>
            <?php endif;?>
        </div>
    </div>

    <div class="col-md-3 no-padding">
        <div class="row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-bell"></i>警示模块</h4>
        </div>
        <div class="box-panel-body">
            <?php if($data['alert']):?>
            <ul class="box-list-ul">
                <li class="box-list-li">
                    <span class="m-tips">待收租机器</span><a class="num-alert"><?=$data['alert']['collect_count']?></a>
                </li>
                <li class="box-list-li">
                    <span class="m-tips">快过期机器</span><a class="num-alert"><?=$data['alert']['expire_count']?></a>
                </li>
            </ul>
            <?php else:?>
                <div class="empty-panel">没有工作任务</div>
            <?php endif;?>
        </div>
        </div>
    </div>
    <div class="col-md-2 no-padding">
        <div class="row-box" style="height: 280px;">
        <a href="<?=Url::toRoute(['adminrent/list'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-phone-alt"></i>电话报修</h4>
        </a>
        <a href="<?=Url::toRoute(['adminscore/send'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-gift"></i>赠送积分</h4>
        </a>
        <a href="<?=Url::toRoute(['machine/list'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-print"></i>查看机器</h4>
        </a>
        <a href="<?=Url::toRoute(['notify/send'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-volume-up"></i>发送通知</h4>
        </a>
        </div>
    </div>
    <div class="col-md-7 no-padding">
        <div class="row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-resize-horizontal"></i>租借申请</h4>
        </div>
        <div class="box-panel-body">
            <?php if($data['rent']):?>
                <ul class="fault-list-ul">
                <?php foreach($data['rent'] as $d):?>
                    <li class="fault-list-li">
                        <div class="fault-data">
                            <a href="<?=$d['headimgurl']?>?.jpg" class="fancybox" rel="group"><img class="cover-img" src="<?=substr($d['headimgurl'],0,-1)?>46" /></a>
                            <h4><?=$d['name']?></h4>
                            <span><?=$d['phone']?></span>
                        </div>
                        <div class="fault-data">
                            <h4><?=$d['brand'],$d['type']?></h4>
                            <span>月租:<b class="high-show"><?=$d['lowest_expense']?></b> , 黑白:<b class="high-show"><?=$d['black_white']?></b> , 彩色:<b class="high-show"><?=$d['colours']?></b></span>
                        </div>
                        <div class="fault-btn">
                            <button type="button" class="btn btn-info btn-sm">通过</button>
                            <button type="button" class="btn btn-danger btn-sm">不通过</button>
                        </div>
                    </li>
                <?php endforeach;?>
                </ul>
            <?php else:?>
            <div class="empty-panel">没有工作任务</div>
            <?php endif;?>
        </div>
        <?php if(count($data['rent'])>3):?>
            <div class="list-more" title="展开">
                <i class="glyphicon glyphicon-menu-down"></i> 展开
            </div>
        <?php endif;?>
        </div>
    </div>
</div>

<?php
// fancybox 图片预览插件
echo newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '100%',
        'maxHeight' => '100%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '100%',
        'height' => '100%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);
?>

<script>
    <?php $this->beginBlock('JS_END') ?>
    ///////////  展开 收起  功能
    var listClsoe = '<i class="glyphicon glyphicon-menu-up"></i> 收起';
    var listShow = '<i class="glyphicon glyphicon-menu-down"></i> 展开';
    $('.list-more').click(function(){
        var p = $(this).closest('.row-box');
        if(p.hasClass('list-all') ){
            p.removeClass('list-all');
            $(this).attr('title','展开').html( listShow );
        }else{
            p.addClass('list-all');
            $(this).attr('title','收起').html( listClsoe );
        }
    });



    <?php $this->endBlock();?>
</script>

<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
