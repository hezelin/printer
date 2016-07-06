<?php
use yii\helpers\Url;
$this->title = '维修任务';
?>

<?= $this->render('_detail', ['model' => $model ]) ?>

<?=$btnHtml?>

<style>
    #fault-money-fixed{ position: fixed; z-index: 19999; background-color: #fff;
        border-top: 1px solid #cccccc;  box-shadow: 0 2px 10px #ccc;
        padding: 25px 0 10px; width: 100%; bottom: 0; display: none;
        left: 0;
    }
    #fault-money-close{ position: absolute; top: 0; color: #666; text-align: center;
        right: 0; width: 40px; height: 40px; font-size: 24px;}
</style>
<div id="fault-money-fixed">
    <div id="fault-money-close">×</div>
    <input type="text" class="h-input" placeholder="维修金额" name="fault-cost" id="fault-cost"/>
    <button type="button" id="fault-money-sub" class="h-button">维修完成</button>
</div>
<script>
<?php $this->beginBlock('JS_END') ?>
    var ms = <?=$model['status']?>;
    var hasClick = 0;

    $(function(){
       $('.process-btn').click(function(){
           var href = $(this).attr('href');
           var status = $(this).attr('data-status');
           var $this = $(this);

           if( $(this).attr('data-ajax') == "0"){
               window.location.href = href;
               return false;
           }
//            维修完成
           if( $(this).attr('data-ajax') == "2"){
               $('#fault-money-fixed').show();
               return false;
           }

           if(hasClick == 1)    return false;
           hasClick = 1;

           $this.addClass('h-loading').text('请求中...');
           $.post(
               href,
               {'status':status},
               function(res){
                   if(res.status == 1){
                       $this.attr({'data-status':res.dataStatus,'href':res.href,'data-ajax':res.dataAjax}).text(res.btnText);
                   }else
                       alert(res.msg);
                   hasClick = 0;
                   $this.removeClass('h-loading');
               },'json'
           );
           return false;
       });

        $('#fault-money-close').click(function(){
            $('#fault-money-fixed').hide();
        });

        $('#fault-money-sub').click(function(){
            var href = $(this).attr('href');
            var status = $(this).attr('data-status');
            var $this = $(this);
            var reg = /^(\d+)(\.\d{1,2})?$/;
            var val = $('#fault-cost').val();
            if($.trim(val) && !reg.test(val) ){
                alert('请输入正确金额（2位小数）!');
                return false;
            }
            $this.addClass('h-loading').text('请求中...');
            $.post(
                '<?=Url::toRoute(['/maintain/task/processajax','id'=>$model['id'],'openid'=>$openid])?>',
                {'status':8,'fault_cost':val},
                function(res){
                    if(res.status == 1){
                        location.href = '/wechat/index/<?=$model['wx_id']?>';
                    }else
                        alert(res.msg);
                },'json'
            );
            return false;
        });
    });
<?php $this->endBlock();?>
</script>
<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    \app\components\WxjsapiWidget::widget([
        'wx_id'=>$model['wx_id'],
        'apiList'=>['previewImage','openLocation'],
        'jsReady'=>'
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
    ]);
?>