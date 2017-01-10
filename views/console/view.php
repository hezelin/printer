<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\shop\models\Shop;
use yii\bootstrap\Modal;



    $this->title = '工作任务';
//$this->registerCssFile('/css/console-task.css');
?>
<link href="/css/console-task.css" rel="stylesheet">
<link href="/css/pnotify.css"  media="all" rel="stylesheet">
<link href="/css/pnotify.history.css"  media="all" rel="stylesheet">
<link href="/css/pnotify.buttons.css"  media="all" rel="stylesheet">
<style type="text/css">
    .ui-pnotify-history-container{
        width: 120px;
        background-color: #616774;
    }
    .ui-pnotify-history-header{
        color:white;
        font-size: 20px;
    }
    .ui-pnotify-history-all,.ui-pnotify-history-last{
        margin:10px 0 5px 0px;
        width:80%;
    }

    .ui-pnotify-history-pulldown{
        color:wheat;
    }
</style>
<p>&nbsp;</p>
<div class="row">
    <div class="mod_navbar">
        <div class="title"> <h3><i class="icon glyphicon glyphicon-tasks"></i> 工作任务</h3>  </div>
    </div>
</div>
<div class="row" id="task-layout">
    <p style="height: 40px;">&nbsp;</p>

    <div class="col-md-9 no-padding">
        <div class="row-box">
            <div class="box-panel-header">
                <h4><i class="icon glyphicon glyphicon-wrench"></i>待分配维修</h4>
            </div>
            <div class="box-panel-body" id="box-fault">
                <?php if($data['fault']):?>
                    <ul class="fault-list-ul" id="fault-list-ul">
                        <?php foreach($data['fault'] as $d):?>
                            <li class="fault-list-li">
                                <div class="fault-data">
                                    <a href="<?=$d['cover']?>" class="fancybox" rel="group"><img class="cover-img" src="<?=$d['cover']?>" /></a>
                                    <h4><?=\app\models\ConfigBase::getFaultStatus($d['type'])?>,
                                        <b class="fault-time"><?=date('m月d H:i',$d['add_time'])?></b>
                                    </h4>
                                    <span data-toggle="tooltip" data-placement="auto" title="<?=$d['desc']?>" ><?=$d['desc']?></span>
                                </div>
                                <div class="fault-data">
                                    <h4><?=$d['brand'],$d['model'],' , ',$d['name']?></h4>
                                    <span data-toggle="tooltip" data-placement="auto" title="<?=$d['phone'],',',$d['address']?>"><?=$d['phone'],',',$d['address']?></span>
                                </div>
                                <div class="fault-btn">
                                    <button type="button" key-id="<?=$d['id']?>" modal-type="modal-fault-allot" class="order-modal btn btn-info btn-sm">分配</button>
                                    <button type="button" key-id="<?=$d['id']?>" modal-type="modal-fault-del" class="order-modal btn btn-danger btn-sm">关闭</button>
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
                        <a href="<?=Url::toRoute(['/service/list','fromFault'=>$d['openid']]);?>" >
                        <span class="m-name"><?=$d['longitude']? '<i class="glyphicon glyphicon-map-marker"></i>':''?><?=$d['name']?></span><span class="m-tips">待修机器</span><span class="num-alert"><?=$d['wait_repair_count']?></span>
                        </a>
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
            <div class="box-panel-body" id="box-order">
                <?php if($data['order']):?>
                    <ul class="order-list-ul" id="order-list-ul">
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
                                <button type="button" key="<?=$d['order_id'],'|',$d['pay_status']?>" class="order-pass btn btn-info btn-sm">通过</button>
                                <button type="button" key-id="<?=$d['order_id']?>" modal-type="modal-order-nopass" class="order-modal btn btn-danger btn-sm">不通过</button>
                            <?php else:?>
                                <button type="button" key-id="<?=$d['order_id']?>" modal-type="modal-order-send" class="order-modal btn btn-info btn-sm">发货</button>
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
            <div class="box-panel-body" id="box-part">
                <?php if($data['part']):?>
                    <ul class="order-list-ul" id="part-list-ul">
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
                                                echo '<a data-href="'.Url::toRoute([Url::toRoute(['/shop/adminparts/status',
                                                        'id'=>$d['id'],
                                                        'status'=>$d['fault_id']>0? 3:2
                                                    ])]).'" class="data-btn1 btn btn-info btn-sm">发货</a>';
                                                break;
                                            case 11:
                                                echo '<a data-href="'.Url::toRoute([Url::toRoute(['/shop/adminparts/status',
                                                        'id'=>$d['id'],
                                                        'status'=>'12'
                                                    ])]).'" class="data-btn1 btn btn-info btn-sm">回收</a>';
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

    <div class="col-md-2 no-padding">
        <div class="row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-bell"></i>警示模块</h4>
        </div>
        <div class="box-panel-body">
            <?php if($data['alert']):?>
            <ul class="box-list-ul">
                <li class="box-list-li">
                    <span class="m-tips" style="width:60%;">待收租机器</span><a href="<?=Url::toRoute(['/admin-rent/collect'])?>" class="num-alert"><?=$data['alert']['collect_count']?></a>
                </li>
                <li class="box-list-li">
                    <span class="m-tips" style="width:60%;">快到期租借</span><a href="<?=Url::toRoute(['/admin-rent/expire'])?>" class="num-alert"><?=$data['alert']['expire_count']?></a>
                </li>
            </ul>
            <?php else:?>
                <div class="empty-panel">没有工作任务</div>
            <?php endif;?>
        </div>
        </div>
    </div>
    <div class="col-md-10 no-padding">
        <div class="row-box">
            <div class="box-panel-header">
                <h4><i class="icon glyphicon glyphicon-yen"></i>机器租金</h4>
            </div>
            <div class="box-panel-body" id="box-rental">
                <?php if($data['rental']):?>
                    <ul class="fault-list-ul" id="rental-list-ul">
                        <?php foreach($data['rental'] as $d):?>
                            <li class="fault-list-li">
                                <div class="fault-data">
                                    <a href="<?=$d['sign_img']?>" class="fancybox" rel="group"><img class="cover-img" src="<?=$d['sign_img']?>" width="40" /></a>
                                    <h4><?=$d['brand'],$d['model'],' , ',$d['name']?></h4>
                                    <span data-toggle="tooltip" data-placement="auto" title="<?=$d['address']?>"><?=$d['address']?></span>
                                </div>
                                <div class="fault-data">
                                    <h4><?=$d['username']?></h4>
                                    <span style="width: 100%" html="true" data-toggle="tooltip" data-placement="auto" title="租金: <?=$d['total_money']?>, 黑白: <?=$d['black_white']?><?php if($d['colour']) echo ', 彩色: '.$d['colour']; if($d['exceed_money']!='0.00') echo ', 超出金额: ',$d['exceed_money'];?>">租金:<b class="high-show"><?=$d['total_money']?></b> , 黑白:<b class="high-show"><?=$d['black_white']?></b><?php if($d['colour']) echo ', 彩色:<b class="high-show">',$d['colour'],'</b>'; if($d['exceed_money']!='0.00') echo ', 超出金额:<b class="high-show">',$d['exceed_money'],'</b>';?></span>
                                </div>
                                <div class="fault-btn">
                                    <button class="btn btn-info btn-sm rental-pass" key-id="<?=$d['id']?>" >通过</button>
                                    <a class="btn btn-danger btn-sm" href="<?=Url::toRoute(['charge/update','id'=>$d['id']])?>">编辑</a>
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

    <div class="col-md-2 no-padding">
        <div class="row-box" style="height: 280px;">
        <a href="<?=Url::toRoute(['service/call'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-phone-alt"></i>电话报修</h4>
        </a>
        <a href="<?=Url::toRoute(['admin-score/send'])?>" class="box-panel-header">
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
    <div class="col-md-10 no-padding">
        <div class="row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-resize-horizontal"></i>租借申请</h4>
        </div>
        <div class="box-panel-body" id="box-rent">
            <?php if($data['rent']):?>
                <ul class="fault-list-ul" id="rent-list-ul">
                <?php foreach($data['rent'] as $d):?>
                    <li class="fault-list-li">
                        <div class="fault-data">
                            <a href="<?=$d['headimgurl']?>?.jpg" class="fancybox" rel="group"><img class="cover-img" src="<?=substr($d['headimgurl'],0,-1)?>46" /></a>
                            <h4><?=$d['name']?></h4>
                            <span data-toggle="tooltip" data-placement="auto" title="联系方式: <?=$d['phone']?>"><?=$d['phone']?></span>
                        </div>
                        <div class="fault-data">
                            <h4><?=$d['brand_name'],$d['model']?></h4>
                            <span data-toggle="tooltip" data-placement="auto" title="月租: <?=$d['lowest_expense']?> , 黑白: <?=$d['black_white']?> , 彩色: <?=$d['colours']?>">月租:<b class="high-show"><?=$d['lowest_expense']?></b> , 黑白:<b class="high-show"><?=$d['black_white']?></b> , 彩色:<b class="high-show"><?=$d['colours']?></b></span>
                        </div>
                        <div class="fault-btn">
                            <a href="<?=Url::toRoute(['/admin-rent/pass','id'=>$d['id']])?>" class="btn btn-info btn-sm">通过</a>
                            <button type="button" key-id="<?=$d['id']?>" modal-type="modal-rent-apply" class="order-modal btn btn-danger btn-sm">不通过</button>
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



Modal::begin([
    'header' => '分配任务',
    'id' => 'modal-fault-allot',
    'size' => 'modal-lg',
    'toggleButton' => false,
    'footer' => '
        <button id="go-back" type="button" class="btn btn-default" data-toggle="list">切换列表分配</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
    ',
]);

echo Html::beginForm('','',['class'=>'form-horizontal','id'=>'fault-text-form']);
echo Html::textInput('fault_remark','',['placeholder'=>'给维修员留言（可省略）','class'=>'form-control','id'=>'fault-remark']);
echo '<br/>';
echo Html::tag('div','',['id'=>'my-fix-model']);                // 地图弹窗


echo Html::beginTag('table',['class'=>'table table-striped','id'=>'my-fix-model-list','style'=>'display:none']);
echo '<tr><th>名字</th><th>手机</th><th>待修</th><th>分配</th></tr>';
foreach($data['maintainer'] as $d){
    echo '<tr><td>',$d['name'],'</td><td>',$d['phone'],'</td><td class="repair-count">',$d['wait_repair_count'],'</td><td><a class="select-maintain" href="javascript:void(0);" title="分配维修" key-wid="',$d['wx_id'],'" key-openid="',$d['openid'],'" data-method="post"><i class="glyphicon glyphicon-ok"></i></a></td></tr>';
}
echo Html::endTag('table');

echo Html::endForm();
Modal::end();


/*
 * 取消任务 模态框
 */
Modal::begin([
    'header' => '关闭维修申请',
    'id' => 'modal-fault-del',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">取消维修</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','取消维修并且给用户和管理员发送通知',['class'=>'text-primary']);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'取消原因','class'=>'form-control','id'=>'cancel-text']);
echo Html::endForm();

Modal::end();

/*
 * 订单不通过
 */
Modal::begin([
    'header' => '不通过原因',
    'id' => 'modal-order-nopass',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="order-cancel-btn" type="button" class="btn btn-primary">提交</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','',['class'=>'text-danger','id'=>'order-cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'不超过200字','class'=>'form-control','id'=>'order-cancel-text']);
echo Html::endForm();

Modal::end();

/*
 * 订单发货
 */
Modal::begin([
    'header' => '发货',
    'id' => 'modal-order-send',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="order-send-btn" type="button" class="btn btn-primary">确定发货</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','',['class'=>'text-danger','id'=>'order-send-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::dropDownList('express_type','',Shop::getExpress(),['class'=>'form-control','id'=>'express_type']);
echo '<br/>';
echo Html::textInput('express_no','',['placeholder'=>'订单编号','class'=>'form-control','id'=>'express_no']);
echo Html::endForm();

Modal::end();


/*
 * 取消租借 模态框
 */
Modal::begin([
    'header' => '租借申请不通过',
    'id' => 'modal-rent-apply',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="rent-apply-btn" type="button" class="btn btn-primary">不通过</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','不通过并且给用户发送通知',['class'=>'text-primary']);
echo Html::tag('p','',['class'=>'text-danger','id'=>'rent-apply-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'资料不齐全无法联系','class'=>'form-control','id'=>'rent-apply-text']);
echo Html::endForm();

Modal::end();

?>

<script src="http://api.map.baidu.com/api?v=2.0&ak=74af171e2b27ee021ed33e549a9d3fb9"></script>

<!--<script type="text/javascript" src="/js/require.js"></script>-->
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/pnotify.js"></script>
<script type="text/javascript" src="/js/pnotify.history.js"></script>
<script type="text/javascript" src="/js/pnotify.buttons.js"></script>


<script>
    // 共用key,fault
    var keyId,
        $li;

    var myMarker,
        mySite,
        mpoints = [],
        mapHasShow = 0,
        mapHei,
        mapFaultData = <?=json_encode($data['maintainer'],JSON_UNESCAPED_UNICODE)?> || [],
        mapHomeData = {
            lng: 116.404,
            lat: 39.915
        };

    function showMap()
    {
        //[20161220 修改Bug
        $('#my-fix-model').show();
        $('#my-fix-model-list').hide();
        $('#go-back').text('切换列表分配');
        $('#go-back').attr('data-toggle', 'list');
        //20161220]
        if(mapHasShow == 0)
        {
            mapHei = mapHei || $(window).height();
            $('#my-fix-model').css({
                height:mapHei-250
            });
            setTimeout(function(){
                if(mySite== undefined){
                    mySite = new BMap.Map("my-fix-model", {enableMapClick: false}); // 创建Map实例
                    mySite.enableScrollWheelZoom();                            // 启用滚轮放大缩小 map.enableContinuousZoom();                             // 启用地图惯性拖拽，默认禁用 map.enableInertialDragging();                           // 启用连续缩放效果，默认禁用。 map.addControl(new BMap.NavigationControl());           // 添加平移缩放控件
                    mySite.addControl(new BMap.NavigationControl());

                    // 维修任务坐标
                    /*var pt = new BMap.Point(mapHomeData.lng, mapHomeData.lat);
                     var myIcon = new BMap.Icon("/images/home-zulin.png", new BMap.Size(38,38));
                     var marker2 = new BMap.Marker(pt,{icon:myIcon});
                     mySite.addOverlay(marker2);*/

                    for (var i = 0; i < mapFaultData.length; i++) {
                        var lat = mapFaultData[i]['latitude'];
                        var lng = mapFaultData[i]['longitude'];
                        var content = '\
                                <div id="openid-'+i+'" class="map-point-label" key-wid="'+mapFaultData[i]['wx_id']+'" key-openid="'+mapFaultData[i]['openid']+'">\
                                    <div class="obj-img">\
                                        <img src="/images/weixiuyuan_01.png">\
                                        <div class="map-img-name">'+mapFaultData[i]['name']+'</div>\
                                    </div>\
                                    <div class="open-box hidden">\
                                        <span class="map-point-name" style="font-size: 17px; margin:12px 0 7px 0;"> '+mapFaultData[i]['name']+'&nbsp;'+(mapFaultData[i]['phone'] == null? '':mapFaultData[i]['phone'])+'</span>\
                                        <span class="map-point-name" style="font-size: 14px; color:#888; margin:4px 0 2px 0; line-height: 24px;">\
                                            <i class="glyphicon glyphicon-time"></i>\
                                            '+mapFaultData[i]['point_time']+'\
                                            <br/>\
                                            <i class="glyphicon glyphicon glyphicon-list-alt"></i>\
                                            待维修'+mapFaultData[i]['wait_repair_count']+'个\
                                        </span>\
                                        <div class="map-yes-fix-btn">\
                                            确认分配\
                                        </div>\
                                    </div>\
                                </div>';
                        var point = new BMap.Point(lng, lat);
                        if(lng == null)
                            continue;
                        mpoints.push(point);
                        var labelOpts = {
                            position: point
                        };
                        var defaultLabel = new BMap.Label(content, labelOpts);
                        mySite.addOverlay(defaultLabel);
                    }
                    mySite.setViewport(mpoints);
                }
            },500);
            mapHasShow = 1;
        }

    }

    <?php $this->beginBlock('JS_END') ?>



    ///////////  展开 收起  功能
    var listClsoe = '<i class="glyphicon glyphicon-menu-up"></i> 收起';
    var listShow = '<i class="glyphicon glyphicon-menu-down"></i> 展开';
    $('#task-layout .list-more').click(function(){
        var p = $(this).closest('.row-box');
        if(p.hasClass('list-all') ){
            p.removeClass('list-all');
            $(this).attr('title','展开').html( listShow );
        }else{
            p.addClass('list-all');
            $(this).attr('title','收起').html( listClsoe );
        }
    });

//    配件处理按钮
    $('#task-layout .data-btn1').click(function(){
        var url = $(this).attr('data-href');
        var $this = $(this);
        $.getJSON(url,'',function(resp){
            if(resp.status ==1){
                $this.closest('li').slideUp();
            }else
                alert(resp.msg);
        });
    })
//    订单通过按钮
    $('#task-layout .order-pass').click(function(){
        var key = $(this).attr('key');
        var $this = $(this);
        var $btn = $(this).closest('.order-btn');
        $.post(
            '<?=Url::toRoute(['/shop/adminorder/pass-by-one'])?>',
            {'data':key},
            function(res){
                if(res.status == 1){
                    if(res.new_status == 5){
                        $btn.html('<button type="button" key-id="'+key.substr(0,18)+'" modal-type="modal-order-send" class="order-modal btn btn-info btn-sm">发货</button>');
                    }else{
                        $this.closest('li').slideUp();
                    }
                }
                else
                    alert(res.msg);
            },'json'
        );

        return false;
    });

//    弹出模态框,order-send,order-nopass,fault-allot,fault-del,rent-apply
    $('#task-layout').on('click','.order-modal',function(){
        var modelIds = $(this).attr('modal-type');
        if( $(this).attr('key-id') != "undefined")
            keyId = $(this).attr('key-id');
        $li = $(this).closest('li');
        $('#'+modelIds).modal('show');
        console.log(modelIds);
        if(modelIds == 'modal-fault-allot')
            showMap();
    });


    //        切换列表模式
    $('#go-back').click(function(){
        var tog = $(this).attr('data-toggle');
        console.log(tog);
        if( tog == 'list') {
            $('#my-fix-model-list').show();
            $('#my-fix-model').hide();
            $(this).attr('data-toggle','map');
            $(this).text('切换地图分配');

        }else{
            $(this).attr('data-toggle','list');
            $('#my-fix-model').show();
            $('#my-fix-model-list').hide();
            $(this).text('切换列表分配');

        }
    });


    $('#modal-fault-allot').on('mouseenter','.map-point-label',function(){
        $(this).find('.obj-img').siblings().removeClass('hidden');
    }).on('mouseleave','.map-point-label',function(){
        $(this).find('.obj-img').siblings().addClass('hidden');
    });

//    地图维修分配
    $('#modal-fault-allot').on('click','.map-yes-fix-btn',function(){

        var $this = $(this);
        var $closest = $this.closest('.map-point-label');

        $this.html('正在分配中 <img src="/images/loading.gif">');
        var wid = $closest.attr('key-wid');
        var openid = $closest.attr('key-openid');
        $.post(
            '<?=Url::toRoute(['/service/allot'])?>',
            {'id':keyId,'wid':wid,'openid':openid,'fault_remark':$('#fault-remark').val()},
            function(res){
                if(res.status == 1){
                    setTimeout(function(){
                        $('#modal-fault-allot').modal('hide');
                        $this.html('确认分配');
                        $li.slideUp();
                    },1000);
                }
                else
                    alert(res.msg);
            },'json'
        );
        return false;
    });

    //    列表维修分配
    $('#modal-fault-allot').on('click','.select-maintain',function(){
        $(this).html('<img src="/images/loading.gif">');
        var $this = $(this);
        var wid = $(this).attr('key-wid');
        var openid = $(this).attr('key-openid');
        var re_count = $(this).closest('tr').children('.repair-count');
        $.post(
            '<?=Url::toRoute(['/service/allot'])?>',
            {'id':keyId,'wid':wid,'openid':openid,'fault_remark':$('#fault-remark').val()},
            function(res){
                if(res.status == 1){
                    re_count.text( parseInt(re_count.text()) + 1 );
                    setTimeout(function(){
                        $this.html('<i class="glyphicon glyphicon-ok"></i>');
                        $('#modal-fault-allot').modal('hide');
                        $li.slideUp();
                    },1000);
                }
                else
                    alert(res.msg);
            },'json'
        );
        return false;
    });

//    维修关闭
    $('#cancel-btn').click(function(){
        var text = $.trim($('#cancel-text').val());
        if(!text){
            $('#cancel-status').text('请输入取消原因！');
            $('#cancel-text').focus();
            return false;
        }
        $.post(
            '<?=Url::toRoute(['/service/delete','id'=>$wx_id])?>?fid='+keyId,
            {'type':1,'text':text},
            function(res){
                if(res.status == 1){
                    $('#modal-fault-del').modal('hide');
                    $li.slideUp();
                }
                else
                    alert(res.msg);
            },'json'
        );
    })
//    订单不通过
    $('#order-cancel-btn').click(function(){
        var text = $.trim($('#order-cancel-text').val());
        if(!text){
            $('#order-cancel-status').text('请输入不通过原因！');
            $('#order-cancel-text').focus();
            return false;
        }
        var orderIds = [];
        orderIds.push(keyId);
        $.post(
            '<?=Url::toRoute(['/shop/adminorder/unpass'])?>',
            {'text':text,'data[]':orderIds},
            function(res){
                if(res.status == 1){
                    $('#modal-order-nopass').modal('hide');
                    $li.slideUp();
                }
                else
                    alert(res.msg);
            },'json'
        );
    });
//    订单发货
    $('#order-send-btn').click(function(){
        var text = $.trim($('#express_no').val());
        var expressType = $('#express_type').val();
        if(expressType !='0' && !text) {
            $('#order-send-status').text('请输入快递单号内容！');
            $('#express_no').focus();
            return false;
        }

        $.post(
            '<?=Url::toRoute(['/shop/adminorder/ajax'])?>?order_id='+keyId,
            {type:expressType,no:text},
            function(res){
                if(res.status == 1){
                    $('#modal-order-send').modal('hide');
                    $li.slideUp();
                }
                else
                    alert(res.msg);
            },'json'
        );
    });
//    租借不通过
    $('#rent-apply-btn').click(function(){
        var text = $.trim($('#rent-apply-text').val());
        if(!text){
            $('#rent-apply-status').text('请输入不通过原因！');
            $('#rent-apply-text').focus();
            return false;
        }
        $.post(
            '<?=Url::toRoute(['/admin-rent/nopass'])?>?rent_id='+keyId,
            {'text':text},
            function(res){
                if(res.status == 1){
                    $('#modal-rent-apply').modal('hide');
                    $li.remove();
                }
                else
                    alert(res.msg);
            },'json'
        );
    });

//    机器租金 通过审核
    $('.rental-pass').click(function(){
        keyId = $(this).attr('key-id');
        $li = $(this).closest('li');
        $.post(
            '<?=Url::toRoute(['/charge/pass'])?>',
            {logId:keyId},
            function(resp){
                if(resp.status == 1)
                    $li.slideUp();
                else
                    alert(resp.msg);
            },'json'
        );
    });

    //初始化
    var newsTimer;
    var counter = 0;
    var fromtime;
    var timeset = 1;//轮询的时间间隔：5分钟
    var notify_delay = 1000*60*1;//消息提示框停留时间：1分钟
    var notify_type = 'info';


    var type = 1;
    $(function(){
        //showNotice('【<b class="high-show">开始自动消息提示</b>】', '如果有新消息，之后会有弹出提示框！','success');
        showNotice('【<b class="high-show">开始自动消息提示</b>】', '如果有新消息，之后会有弹出提示框！','success', 1000*30);


        $('.ui-pnotify-history-all').addClass('btn-warning');
        $('.ui-pnotify-history-last').addClass('btn-info');
        startTimer();
    });


    //开启计时器
    function startTimer() {

        newsTimer = setInterval(function () {
            //alert(123);
            counter++;


            fromtime = parseInt( (new Date().getTime())/1000 - timeset*60);//获取当前时间
            $.post('<?=Url::toRoute(['/console/polling'])?>',{'fromtime':fromtime},function(rst){
                var result = JSON.parse(rst);
                var data = result['data'];




                //1. 待分配维修处理
                if(data['fault'].length > 0){
                    if ($('#fault-list-ul').length <= 0) {
                        $('#box-fault').html("<ul class='fault-list-ul' id='fault-list-ul'></ul>");
                    }
                    for(var i = 0; i < data['fault'].length; i ++){
                        var content = JSON.parse(data['fault'][i]['content']);
                        //alert(content);
                        var cover = content['cover'];
                        $('#fault-list-ul').prepend("<li class='fault-list-li'><div class='fault-data'><a href='"+cover+"' class='fancybox' rel='group'><img class='cover-img' src='"+cover+"' /></a><h4>"+data['fault'][i]['type'] + ", <b class='fault-time'>"+data['fault'][i]['add_time']+"</b></h4><span>"+data['fault'][i]['desc']+"</span></div><div class='fault-data'><h4>"+data['fault'][i]['brand']+data['fault'][i]['model']+" , "+data['fault'][i]['name']+"</h4><span>"+data['fault'][i]['phone']+","+data['fault'][i]['address']+"</span></div><div class='fault-btn'><button type='button' key-id='"+data['fault'][i]['id']+"' modal-type='modal-fault-allot' class='order-modal btn btn-info btn-sm'>分配</button><button type='button' key-id='"+data['fault'][i]['id']+"' modal-type='modal-fault-del' class='order-modal btn btn-danger btn-sm'>关闭</button></div></li>");

                        showNotice('你有一个新的【<b class="high-show">维修分配</b>】',data['fault'][i]['address']+':'+data['fault'][i]['type'], notify_type, notify_delay);
                    }
                }

                //2. 订单处理
                if(data['order'].length > 0){
                    if($('#order-list-ul').length <=0 ){
                        $('#box-order').html("<ul class='order-list-ul' id='order-list-ul'></ul>");
                    }
                    for(var i = 0; i < data['order'].length; i ++){
                        var order_data = data['order'][i]['order_data'];
                        for(var j = 0; j < order_data.length; j ++) {
                            var str = "<li class='order-list-li'><ul class='order-list-m'><li><a href='/shop/backend/view?id=" + order_data[j]['item_id'] + "'><img class='order-cover' src='"+order_data[j]['cover']+"'><span class='order-name'>"+order_data[j]['name']+"</span><p class='order-num-price'>"+order_data[j]['item_nums']+" 件 <b>¥"+(order_data[j]['item_nums']*order_data[j]['price']).toFixed(2)+"</b></p></a></li></ul><div class='order-list'><p>支付金额: <span style='color:#b10000;font-weight: 600'> ¥"+data['order'][i]['total_price']+"</span></p><p>积分支付: <span>"+data['order'][i]['pay_score']+"</span></p><p>其中运费: <span>"+data['order'][i]['freight']+"</span></p><p>方式: <span>"+data['order'][i]['pay_status']+"</span></p></div><ul class='order-address'><li>"+data['order'][i]['name']+data['order'][i]['phone']+"</li><li>"+data['order'][i]['city']+data['order'][i]['address']+"</li><li class='order-remark'>"+data['order'][i]['remark']+"</li></ul><div class='order-btn'>";
                          if(data['order'][i]['order_status'] == 1) {
                              str += "<button type='button' key='"+data['order'][i]['order_id']+"|"+data['order'][i]['pay_status']+"' class='order-pass btn btn-info btn-sm'>通过</button><button type='button' key-id='"+data['order'][i]['order_id']+"' modal-type='modal-order-nopass' class='order-modal btn btn-danger btn-sm'>不通过</button></div></li>"
                          }else {
                              str += "<button type='button' key-id='201610251002429341' modal-type='modal-order-send' class='order-modal btn btn-info btn-sm'>发货</button></div></li>";
                          }

                            $('#order-list-ul').prepend(str);
                        }
                        showNotice('你有一个新的【<b class="high-show">订单</b>】',data['order'][i]['address']+':'+data['order'][i]['name'], notify_type, notify_delay);
                    }

                }

                //3. 配件处理
                if(data['part'].length > 0){
                    if($('#part-list-ul').length <= 0){
                        $('#box-part').html("<ul class='order-list-ul' id='part-list-ul'></ul>");
                    }
                    for(var i = 0; i < data['part'].length; i ++){
                        var str = "<li class='order-list-li'><ul class='order-list-m'><li class='part-li'><a href='/shop/backend/view?id="+data['part'][i]['item_id']+"'><img class='order-cover' src='"+data['part'][i]['cover']+"'><span class='order-name'>"+data['part'][i]['name']+"</span><p class='order-num-price'>销售价格<b>¥"+data['part'][i]['price']+"</b></p></a></li></ul><div class='part-data'>";
                        if(data['part'][i]['type'] == ""){
                            str += "携带申请</div>";
                        }else{
                           str += "<a href='"+data['part'][i]['fault_cover']+"' class='fancybox' rel='group'><img class='cover-img' src='"+data['part'][i]['fault_cover']+"' /></a><h4>"+data['part'][i]['type']+"</h4><span>"+data['part'][i]['desc']+"</span>";
                        }
                        str += "<div class='part-data'><h4>"+data['part'][i]['nickname']+"</h4><span>"+data['part'][i]['phone']+"</span></div>";

                        var status = 0;
                        if(data['part'][i]['status']>0)
                            status = 3;
                        else
                            status = 2;

                        str += "<div class='part-btn'>"+data['part'][i]['status'];
                        if(data['part'][i]['status'] == '申请中'){
                            str += "<a data-href='/shop/adminparts/status?id="+data['part'][i]['id']+"&status="+status+"' class='data-btn1 btn btn-info btn-sm'>发货</a></div></li>";
                        }else{

                            str += "<a data-href='/shop/adminparts/status?id="+data['part'][i]['id']+"&status="+status+"' class='data-btn1 btn btn-info btn-sm'>回收</a></div></li>";
                        }




                        $('#part-list-ul').prepend(str);
                        showNotice('你有一个新的【<b class="high-show">配件申请</b>】',data['part'][i]['nickname']+':'+data['part'][i]['name'], notify_type, notify_delay);
                    }
                }

                //4. 机器租金
                if(data['rental'].length > 0 ){
                    if($('#rental-list-ul').length <= 0 ){
                        $('#box-rental').html("<ul class='fault-list-ul' id='rental-list-ul'></ul>");
                    }
                    for(var i = 0; i < data['rental'].length; i ++){
                        var str = "<li class='fault-list-li'><div class='fault-data'><a href='"+data['rental'][i]['sign_img']+"' class='fancybox' rel='group'><img class='cover-img' src='"+data['rental'][i]['sign_img']+"' width='40' /></a><h4>"+data['rental'][i]['brand']+data['rental'][i]['model']+" , "+data['rental'][i]['name']+"</h4><span>"+data['rental'][i]['address']+"</span></div><div class='fault-data'><h4>"+data['rental'][i]['username']+"</h4><span>租金:<b class='high-show'>"+data['rental'][i]['total_money']+"</b> , 黑白:<b class='high-show'>"+data['rental'][i]['black_white']+"</b>, 彩色:<b class='high-show'>"+data['rental'][i]['colour']+"</b>";
                        if(data['rental'][i]['exceed_money'] != "0.00"){
                            str += ", 超出金额:<b class='high-show'>"+data['rental'][i]['exceed_money']+"</b></span>";
                        }else{
                            str += "</span>";
                        }
                        str += "</span></div><div class='fault-btn'><button class='btn btn-info btn-sm rental-pass' key-id='"+data['rental'][i]['id']+"' >通过</button><a class='btn btn-danger btn-sm' href='/charge/update/"+data['rental'][i]['id']+"'>编辑</a></div></li>";
                        $('#box-rental').prepend(str);

                        showNotice('你有一个新的【<b class="high-show">租金提交</b>】',data['rental'][i]['username']+':￥'+data['rental'][i]['total_money'], notify_type, notify_delay);
                    }
                }

                //5. 租借申请
                if(data['rent'].length > 0){
                    if($('#rent-list-ul').length <= 0 ){
                        $('#box-rental').html("<ul class='fault-list-ul' id='rent-list-ul'></ul>");
                    }

                    for(var i = 0; i < data['rent'].length; i ++ ){
                        var url = "";
                        //alert((data['rent'][i]['headimgurl']).length);
                        if(data['rent'][i]['headimgurl'] != ""){
                            url = (data['rent'][i]['headimgurl']).substring(0, (data['rent'][i]['headimgurl']).length-1);
                        }
                        //alert(url);

                        var str = "<li class='fault-list-li'><div class='fault-data'><a href='"+data['rent'][i]['headimgurl']+"?.jpg' class='fancybox' rel='group'><img class='cover-img' src='"+url+"46' /></a><h4>"+data['rent'][i]['name']+"</h4><span>"+data['rent'][i]['phone']+"</span></div><div class='fault-data'><h4>"+data['rent'][i]['brand_name']+data['rent'][i]['model']+"</h4><span>月租:<b class='high-show'>"+data['rent'][i]['lowest_expense']+"</b> , 黑白:<b class='high-show'>"+data['rent'][i]['black_white']+"</b> , 彩色:<b class='high-show'>"+data['rent'][i]['colours']+"</b></span></div><div class='fault-btn'><a href='/admin-rent/pass?id="+data['rent'][i]['id']+"' class='btn btn-info btn-sm'>通过</a><button type='button' key-id='"+data['rent'][i]['id']+"' modal-type='modal-rent-apply' class='order-modal btn btn-danger btn-sm'>不通过</button></div></li>";

                        $('#box-rent').prepend(str);

                        showNotice('你有一个新的【<b class="high-show">租借申请</b>】',data['rent'][i]['name']+':'+data['rent'][i]['brand_name']+data['rent'][i]['model'], notify_type, notify_delay);
                    }
                }



            });//end of post

        }, 1000*60*timeset);//end of newsTimer
    }// end of startTimer

    //展示提示
    function showNotice(title, text, type, delay ) {
////        requirejs(['jquery', 'pnotify', 'pnotify.history','pnotify.buttons'],
//            requirejs(['pnotify'], function($, PNotify){
        if(type != 'success')
            $('#myVideo')[0].play();
            PNotify.prototype.options.styling = "bootstrap3";
            //$(function(){
                new PNotify({
                    title: title,
                    text: text,
                    type:type,
                    delay:delay,
                    hide:true, //是否自动关闭
                    mouse_reset:true,   //鼠标悬浮的时候，计时重置

                    history:{
                        history:true,
                        menu:true,
                        fixed:true,
                        maxonscreen:Infinity ,
                        labels: {redisplay: "历史消息", all: "显示全部", last: "最后一个"}
                    },
                    buttons:{
                        closer:true,
                        closer_hover:false,
                        sticker_hover:true,
                        labels: {close: "关闭", stick: "停止",unstick: "开始"},


                    }



                });
            //});
    }

    //停止计时器
    function stopTimer(){
        clearInterval(newsTimer);
    }


    <?php $this->endBlock();?>

</script>
<video id="myVideo" style="display: none;" controls=""  name="media"><source src="/voices/notify.mp3" type="audio/mpeg"></video>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>

