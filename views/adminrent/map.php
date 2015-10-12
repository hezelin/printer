<?php
    $this->title = '用户定位'
?>

<style type="text/css">
    *{
        margin:0px;
        padding:0px;
    }
    body, button, input, select, textarea {
        font: 12px/16px Verdana, Helvetica, Arial, sans-serif;
    }
</style>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
    <?php $this->beginBlock('JS_END') ?>

    var lat = <?=$model->latitude?>;
    var lng = <?=$model->longitude?>;

    var center,map,marker = null;

    var getMarker = function(){
        marker = new qq.maps.Marker({
            position: center,
            draggable: true,
            map: map
        });
        qq.maps.event.addListener(marker, 'dragend', function() {
            $('#input-lng').val( marker.getPosition().lng );
            $('#input-lat').val( marker.getPosition().lat );
        });
    }

    center = new qq.maps.LatLng(lat,lng);
    map = new qq.maps.Map(document.getElementById('container'),{
        center: center,
        zoom: 13
    });


    if( lat == 0.000000 || lng == 0.000000) {           // 自动获取用户ip 坐标，定位

        var citylocation = new qq.maps.CityService({
            complete: function (result) {
                map.setCenter(result.detail.latLng);
                lat = result.detail.latLng.lat ;
                lng = result.detail.latLng.lng;
                $('#input-lng').val(lng);
                $('#input-lat').val(lat);
                center = new qq.maps.LatLng(lat,lng);
                map = new qq.maps.Map(document.getElementById('container'),{
                    center: center,
                    zoom: 13
                });

                getMarker();

            }
        });
        citylocation.searchLocalCity();
    }else
        getMarker();

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
<?php

if( Yii::$app->session->hasFlash('success') )
    echo \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => Yii::$app->session->getFlash('success'),
    ]);
?>
<form method="post">
    <input name="_csrf" type="hidden" value="<?php echo \Yii::$app->request->csrfToken; ?>"/>
    <input id="input-lat" type="hidden" name="lat" value="<?=$model->latitude?>" />
    <input id="input-lng" type="hidden" name="lng" value="<?=$model->longitude?>" />
<div class="alert alert-info">
    <button id="save-location" type="submit" class="btn btn-info">保存定位</button>
    &nbsp;&nbsp;<?=$model->address?>
    &nbsp;&nbsp;<span style="color:orange;">放大地图，拖动地图上的点，校正坐标</span>
</div>

</form>
<div style="width:100%;height:450px" id="container"></div>
<p id="info" style="margin-top:10px;"></p>

