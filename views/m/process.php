<?php
use yii\helpers\Url;
$this->title = '维修任务';
?>
<style>
    body{ background-color: #ffffff !important;}
</style>

<div class="h-box">
    <div class="h-title h-gray h-hr">故障信息</div>
    <div class="h-box-row h-hr">
        <div class="h-left">
            <img id="previewImage" class="h-img" src="<?=$model['fault_cover']?>" />
        </div>
        <div class="h-right">
            <h4 class="h-row-1 h-hr">故障类型：<?=\app\models\ConfigBase::getFaultStatus($model['fault_type'])?></h4>
            <p class="h-row-2"><?=$model['desc']?></p>
        </div>
    </div>
</div>

<div class="h-box">
    <div class="h-title h-gray h-hr">客户资料</div>
    <div class="h-row">
        <div class="h-left-text">客户信息</div>
        <div class="h-right-text"><?= $model['name'],',<a class="h-tel" href="tel:',$model['phone'],'">',$model['phone']?></a></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">客户地址</div>
        <div class="h-right-text"><?= $model['address']?></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">申请时间</div>
        <div class="h-right-text"><?= date('Y年m月d H:i',$model['add_time'])?></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">地址坐标</div>
        <div class="h-right-text" id="map-btn" style="color: #5c72ff; font-weight: 600;">点击导航</div>
    </div>
    <div class="h-row">
        <div class="h-left-text">历史维修</div>
        <a href="<?=Url::toRoute(['s/irecord','id'=>$model['wx_id'],'mid'=>$model['mid']])?>" class="h-right-text" style="color: #5c72ff; font-weight: 600;">点击查看</a>
    </div>

    <?php if(isset($evaluate) && $evaluate):?>
    <div class="h-row">
        <div class="h-left-text">用户评价</div>
        <div class="h-right-text"><?=$evaluate?> 分</div>
    </div>
    <?php endif;?>
</div>

<?=$btnHtml?>

<script>
<?php $this->beginBlock('JS_END') ?>
    var ms = <?=$model['status']?>;
    var hasClick = 0;
    $(function(){
       $('#process-btn').click(function(){
           if(hasClick == 1)    return false;
           hasClick = 1;
           var href = $(this).attr('href');
           var status = $(this).attr('data-status');
           var $this = $(this);
           $this.addClass('h-loading');
           if( $(this).attr('data-ajax') == "0"){
               window.location.href = href;
               return false;
           }
           $.post(
               href,
               {'status':status},
               function(res){
                   if(res.status == 1){
                       if( res.dataStatus == 9){
                           $('.h-fixed-bottom').html('<a href="'+res.href+'">查看维修进度</a>');
                       }else
                       $this.attr({'data-status':res.dataStatus,'href':res.href,'data-ajax':res.dataAjax}).text(res.btnText);
                   }else
                       alert(res.msg);
                   hasClick = 0;
                   $this.removeClass('h-loading');
               },'json'
           );
           return false;
       })
    });
<?php $this->endBlock();?>
</script>
<?php
    \app\assets\ZeptoAsset::register($this);
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
        };
      '
    ]);
?>