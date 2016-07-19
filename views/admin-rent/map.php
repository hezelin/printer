<?php
$this->title = '租户地址定位';
$this->params['breadcrumbs'][] = $this->title;
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
<form method="post" class="form-inline" id="address-form">
    <input name="_csrf" type="hidden" value="<?php echo \Yii::$app->request->csrfToken; ?>"/>
    <input id="input-lat" type="hidden" name="lat" value="<?=$model->latitude?>" />
    <input id="input-lng" type="hidden" name="lng" value="<?=$model->longitude?>" />
    <div class="alert alert-info">
        <div class="form-group">
            <label for="address-name">地址：</label>
            <input type="text" id="address-name" name="address-name" class="form-control" style="width:350px;" value="<?=$model->address?>" />
            <span style="margin-left: 10px;">输入地址定位坐标</span>
        </div>
        <div style="margin:10px 0;">操作提示：放大地图，拖动地图上的点，校正坐标</div>
        <div id="show-address" style="margin:10px 0;"></div>

        <button id="save-location" type="button" class="btn btn-primary">保存定位</button>
    </div>
</form>

<div style="width:100%;height:450px" id="allmap"></div>
<p id="info" style="margin-top:10px;"></p>


<script src="http://api.map.baidu.com/api?v=2.0&ak=74af171e2b27ee021ed33e549a9d3fb9"></script>
<script>
    <?php $this->beginBlock('JS_END') ?>
    // 百度地图API功能
    var map = new BMap.Map("allmap", {enableMapClick: false});
    var myGeo = new BMap.Geocoder();
    var marker;                                             // 标识的点，拖动
    var city;
    function myFun(result){
        city = result.name;
        map.centerAndZoom(city,12);
    }
    var myCity = new BMap.LocalCity();
    myCity.get(myFun);


    map.enableScrollWheelZoom();                            // 启用滚轮放大缩小 map.enableContinuousZoom();                             // 启用地图惯性拖拽，默认禁用 map.enableInertialDragging();                           // 启用连续缩放效果，默认禁用。 map.addControl(new BMap.NavigationControl());           // 添加平移缩放控件
    map.addControl(new BMap.NavigationControl());

    function getPoint(){
        var p= marker.getPosition();
        document.getElementById('input-lat').value = p.lat;
        document.getElementById('input-lng').value = p.lng;
        console.log(p);
        myGeo.getLocation(p,function(rs){
            var addComp = rs.addressComponents;
            city = addComp.city;
            document.getElementById('show-address').innerHTML = '坐标点地址：' + addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
        });
    }

    $(function(){
        var initAddress = '<?=$model->address?>';

        if( $('#input-lng').val() != 0 ){
            var point = new BMap.Point($('#input-lng').val(),$('#input-lat').val());
            map.centerAndZoom(point, 15);
            marker && map.removeOverlay(marker);
            marker = new BMap.Marker(point);
            map.addOverlay(marker);
            marker.enableDragging();
            getPoint();                                             // 初始化坐标
            marker.addEventListener("dragend",getPoint);
        }
        else if( initAddress ){
            // 将地址解析结果显示在地图上,并调整地图视野
            myGeo.getPoint(initAddress, function(point){
                if (point) {
                    map.centerAndZoom(point, 15);
                    marker && map.removeOverlay(marker);
                    marker = new BMap.Marker(point);
                    map.addOverlay(marker);
                    marker.enableDragging();
                    getPoint();                                             // 初始化坐标
                    marker.addEventListener("dragend",getPoint);
                }else{
                    console.log('地址不存在！');
                }
            });
        }



        $('#address-name').change(function(){
            var address = $.trim($(this).val());
            if( address ){
                // 将地址解析结果显示在地图上,并调整地图视野
                myGeo.getPoint(address, function(point){
                    if (point) {
                        map.centerAndZoom(point, 15);
                        marker && map.removeOverlay(marker);
                        marker = new BMap.Marker(point);
                        map.addOverlay(marker);
                        marker.enableDragging();
                        getPoint();                                             // 初始化坐标
                        marker.addEventListener("dragend",getPoint);
                    }else{
                        console.log('地址不存在！');
                    }
                }, city);
            }
        });

        $('#save-location').click(function(){
           var lng = $('#input-lng').val();
            if(lng == 0)
            {
                alert('地址坐标不存在');
                return false;
            }

            $('#address-form').submit();
        });
    });

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>

