<?php
$this->title = '维修任务';
/*
 * 到达签到页面
 */
?>

<?= $this->render('_detail', ['model' => $model ]) ?>


<?=$btnHtml?>

<script>
    var isScan = 0;
    var mUrl = '<?=$mUrl?>';
    var hasClick = 0;

    function process() {
        if (hasClick == 1)
            return false;

        hasClick = 1;
        var $this = $('.process-btn');

        var href = $this.attr('data-href');
        var status = $this.attr('data-status');
        $this.addClass('h-loading').text('请求中...');
        if ( $this.attr('data-ajax') == "0") {
            window.location.href = href;
            return false;
        }
        $.post(
            href,
            {'status': status},
            function (res) {
                if (res.status == 1) {
                    $this.attr({
                        'data-status': res.dataStatus,
                        'data-href': res.href,
                        'data-ajax': res.dataAjax
                    }).text(res.btnText);
                } else
                    alert(res.msg);
                hasClick = 0;
                $this.removeClass('h-loading');
            }, 'json'
        );
    }
</script>
<?php
    \app\components\WxjsapiWidget::widget([
        'wx_id'=>$model['wx_id'],
        'apiList'=>['scanQRCode','previewImage','openLocation'],
        'jsReady'=>'
        document.querySelector(".process-btn").onclick = function () {
            if(isScan == 1){
                process();
                return false;
            }
            wx.scanQRCode({
                needResult: 1,  // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                    if( mUrl === res.resultStr ){
                        isScan = 1;
                        process();
                    }else{
                        alert("机器不匹配!");
                    }
                }
            });
            return false;
        };

        document.querySelector("#previewImage").onclick = function () {
            wx.previewImage({
              current: "'.$model["fault_cover"].'",
              urls: '.json_encode($model['cover_images']).'
            });
        };

        document.querySelector("#map-btn").onclick = function () {
            wx.openLocation({
                latitude: '.$model["latitude"].',
                longitude: '.$model["longitude"].',
                name: "维修地点",
                address: "'.$model["address"].'",
                scale: 16,
                infoUrl: ""
            });
        };'
    ])
?>