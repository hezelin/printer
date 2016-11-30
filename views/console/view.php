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
            <div class="box-panel-body">
                <?php if($data['rental']):?>
                    <ul class="fault-list-ul">
                        <?php foreach($data['rental'] as $d):?>
                            <li class="fault-list-li">
                                <div class="fault-data">
                                    <a href="<?=$d['sign_img']?>" class="fancybox" rel="group"><img class="cover-img" src="<?=$d['sign_img']?>" width="40" /></a>
                                    <h4><?=$d['brand'],$d['model'],' , ',$d['name']?></h4>
                                    <span><?=$d['address']?></span>
                                </div>
                                <div class="fault-data">
                                    <h4><?=$d['username']?></h4>
                                    <span>租金:<b class="high-show"><?=$d['total_money']?></b> , 黑白:<b class="high-show"><?=$d['black_white']?></b><?php if($d['colour']) echo ', 彩色:<b class="high-show">',$d['colour'],'</b>'; if($d['exceed_money']!='0.00') echo ', 超出金额:<b class="high-show">',$d['exceed_money'],'</b>';?></span>
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
                            <h4><?=$d['brand_name'],$d['model']?></h4>
                            <span>月租:<b class="high-show"><?=$d['lowest_expense']?></b> , 黑白:<b class="high-show"><?=$d['black_white']?></b> , 彩色:<b class="high-show"><?=$d['colours']?></b></span>
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
        <button id="go-back" type="button" class="btn btn-default">上一步</button>
        <button id="next-step" type="button" class="btn btn-primary">下一步</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);

echo Html::beginForm('','',['class'=>'form-horizontal','id'=>'fault-text-form']);
echo Html::textarea('fault_remark','',['placeholder'=>'备注留言(可省略)','class'=>'form-control','id'=>'fault-remark']);
echo Html::endForm();

echo Html::tag('div','',['id'=>'my-fix-model','style'=>'display:none']);

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
        $('#my-fix-model').show();
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
    });


    //        上一步
    $('#go-back').click(function(){
        $('#next-step').show();
        $('#fault-text-form').show();
        $('#my-fix-model').hide();
    });
    //        下一步
//    $('#my-fix-model').hide();
    $('#next-step').click(function(){
        $(this).hide();
        $('#fault-text-form').hide();
//        $('#my-fix-model').show();
        showMap();
    });

    $('#modal-fault-allot').on('mouseenter','.map-point-label',function(){
        $(this).find('.obj-img').siblings().removeClass('hidden');
    }).on('mouseleave','.map-point-label',function(){
        $(this).find('.obj-img').siblings().addClass('hidden');
    });

//    维修分配
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
    })
    <?php $this->endBlock();?>
</script>

<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
