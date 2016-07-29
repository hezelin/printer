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

</style>

<div id="map_canvas"></div>

<script type="text/javascript">

    var data = <?=$points? json_encode($points):'[]'?>;

    <?php $this->beginBlock('JS_END') ?>

    // 设置地图窗口的高度
    var hei = hei || $(window).height();
    $('#map_canvas').css({
        height:hei-130
    });

    var initLat = <?=isset($points[0][0]) && $points[0][0] ? $points[0][0]:39.916527 ?>;
    var initLng = <?=isset($points[0][1]) && $points[0][1] ? $points[0][1]:116.397128 ?>;
    var latLng = new qq.maps.LatLng(initLat,initLng);
    var options = {
        'zoom':13,
        'center':latLng,
        'mapTypeId':"roadmap"
    };

    var map = new qq.maps.Map( document.getElementById('map_canvas'), options);


    //根据 用户浏览器ip 定位
    if( initLat == 39.916527 )
    {
        var citylocation = new qq.maps.CityService({
            complete : function(result){
                map.setCenter(result.detail.latLng);
            }
        });
        citylocation.searchLocalCity();

    }
    var markers = new qq.maps.MVCArray();

    function createCluster() {
        for (var i = 0; i < data.length; i++) {
            var latLng = new qq.maps.LatLng( data[i][0], data[i][1]);
            var marker = new qq.maps.Marker({
                'position':latLng,
                map:map
            });
            markers.push(marker);
        }

        markerClusterer = new qq.maps.MarkerCluster({
            map:map,
            minimumClusterSize:2,       //默认2
            markers:markers,
            zoomOnClick:true,           //默认为true
            gridSize:30,                //默认60
            averageCenter:true,         //默认false
            maxZoom:18                  //默认18
        });

    }

    createCluster();

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJsFile('http://map.qq.com/api/js?v=2.exp',['depends'=>['yii\web\JqueryAsset']]);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>