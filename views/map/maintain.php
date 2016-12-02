<?php
$this->title = '地图分布';
$this->params['breadcrumbs'][] = ['label'=>'维修员资料','url'=>['/staff/list']];
$this->params['breadcrumbs'][] = $this->title;



?>
    <style type="text/css">
        .wrap{padding: 0 !important;}
        #map_canvas {
            width:100%;
        }
        .BMapLabel{ border: none !important; background-color: inherit !important;}
        .point-li{
            width: 150px;
            height: 150px;
            border: none;
            border-top: 2px solid #F1327A;
            margin-left: -76px;
            padding: 10px;
            background-color: #fff !important;
            box-shadow: 0 1px 4px #999;
            margin-top: -180px;
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
    </style>

    <div id="map_canvas"></div>

    <script src="http://api.map.baidu.com/api?v=2.0&ak=74af171e2b27ee021ed33e549a9d3fb9"></script>


    <script type="text/javascript">
        <?php $this->beginBlock('JS_END') ?>
        var mapData = <?=$points? json_encode($points,JSON_UNESCAPED_UNICODE):'[]'?>;



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
        mpoints = [];

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

        var mapFaultData = mapData;
        for (var i = 0; i < mapFaultData.length; i++) {
            var lat = mapFaultData[i]['lat'];
            var lng = mapFaultData[i]['lng'];
            var content = '\
                                <div class="map-point-label" >\
                                    <div class="obj-img">\
                                        <img src="/images/weixiuyuan_01.png">\
                                        <div class="map-img-name" style="position:absolute;top:14px;left:14px;color:white;" >'+mapFaultData[i]['name']+'</div>\
                                    </div>\
                                </div>';
            var point = new BMap.Point(lng, lat);
            if(lng == null)
                continue;
            mpoints.push(point);
//            var labelOpts = {
//                position: pt
//            };
            var defaultLabel = new BMap.Label(content, {position:point,offset:new BMap.Size(-30,-50)});
            map.addOverlay(defaultLabel);
        }
        map.setViewport(mpoints);



        <?php $this->endBlock();?>
    </script>

<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>