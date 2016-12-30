<?php
$this->title = '地图分布';
$this->params['breadcrumbs'][] = ['label'=>'租赁资料','url'=>['/admin-rent/list']];
$this->params['breadcrumbs'][] = $this->title;

?>
<style type="text/css">
    .wrap{padding: 0 !important;}
    #map_canvas {
        width:100%;
    }
    .BMapLabel{ border: none !important; background-color: inherit !important;}
    .point-li{
        width: 170px;
        height: 170px;
        border: none;
        border-top: 2px solid #F1327A;
        margin-left: -86px;
        padding: 10px;
        background-color: #fff !important;
        box-shadow: 0 1px 4px #999;
        margin-top: -200px;
        display: none;
    }
    .point-row{
        padding-bottom:5px;
    }
    .point-row span{
        display: block;
    }
    .point-name {
        display: block;
        padding: 3px 0;
        font-weight: 600;
    }
    .point-li .point-icon {
        width: 28px;
        height: 15px;
        background: url(/img/login-auth.png) -50px -40px no-repeat;
        position: absolute;
        left: -12px;
        bottom: 18px;
    }

    .ellipsis-show{
        display: inline-block;
        width: 100%;
        word-break: keep-all;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>

<div id="map_canvas"></div>

    <script src="http://api.map.baidu.com/api?v=2.0&ak=74af171e2b27ee021ed33e549a9d3fb9"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>

<script type="text/javascript">

    var mapData = <?=$points? json_encode($points,JSON_UNESCAPED_UNICODE):'[]'?>;

    <?php $this->beginBlock('JS_END') ?>

    // 设置地图窗口的高度
    var hei = hei || $(window).height();
    $('#map_canvas').css({
        height:hei-130
    });


    var wid = $('#my-left-nav').width();
    $('#my-left-nav').css({
        height:hei-51,
        width:wid,
        position:'fixed',
        top:51,
        left:0
    });
    $('#my-header-nav .navbar-brand').css({
        width:wid
    });


    var map = new BMap.Map("map_canvas", {enableMapClick: false});

    var initLat = <?=isset($mapData[0]['lat']) && $mapData[0]['lat'] ? $mapData[0]['lat']:39.916527 ?>;
    var initLng = <?=isset($mapData[0]['lng']) && $mapData[0]['lng'] ? $mapData[0]['lng']:116.397128 ?>;
    var city,
        marker,
        markers = [];

    //初始化中心地图
    if( initLat == 39.916527 )
    {
        function myFun(result){
            city = result.name;
            map.centerAndZoom(city,13);
        }
        var myCity = new BMap.LocalCity();
        myCity.get(myFun);

    }else
        map.centerAndZoom(new BMap.Point(initLng,initLat), 13);

    map.enableScrollWheelZoom();                            // 启用滚轮放大缩小 map.enableContinuousZoom();                             // 启用地图惯性拖拽，默认禁用 map.enableInertialDragging();                           // 启用连续缩放效果，默认禁用。 map.addControl(new BMap.NavigationControl());           // 添加平移缩放控件
    map.addControl(new BMap.NavigationControl());           // 启用放大缩小 尺


    function addClickHandler(marker, pointId) {
        marker.addEventListener("click", function (e) {

//            map.centerAndZoom(new BMap.Point($('#point-' + pointId).attr('lng'),$('#point-' + pointId).attr('lat')), 13);

            $('.point-li').hide();
            $('#point-' + pointId).show();
        });
    }

    map.addEventListener("zoomend", function () {
        $('.point-li').hide();
    });
    
    for (var i in mapData) {
        if( parseFloat(mapData[i]['lng'])>0 &&  parseFloat(mapData[i]['lat']) > 0) {
            pt = new BMap.Point(mapData[i]['lng'], mapData[i]['lat']);

            var icon = new BMap.Icon('/img/map_zu.png', new BMap.Size(30, 36));
            if(mapData[i]['status'] == 3)
                var icon = new BMap.Icon('/img/map_xiu.png', new BMap.Size(30, 36));

            marker = new BMap.Marker(pt,{icon: icon});
            addClickHandler(marker, mapData[i]['id']);
            markers.push(marker);

            var defaultLabel = new BMap.Label(mapData[i]['html'], {position: pt});
            map.addOverlay(defaultLabel);
        }
    }

    var markerCluster = new BMapLib.MarkerClusterer(map, {markers:markers});


    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>