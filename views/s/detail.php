<?php
    $this->title = '维修进度';
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
    <div class="h-title h-gray h-hr">维修进度</div>
    <ul class="h-process">
        <?php if($process):?>
            <?php foreach($process as $k=>$p):?>
                <?php echo $k==0? '<li class="h-active">':'<li>';?>
                    <i class="h-icon-circle"></i>
                    <p class="h-text">
                        <?php
                            $content = json_decode($p['content'],true);
                            echo \app\models\ConfigBase::getFixStatus($content['status']);
                        ?>
                    </p>
                    <p class="h-text-2"><?=date('Y-m-d H:i',$p['add_time'])?></p>
                </li>
            <?php endforeach;?>
            <li>
        <?php else:?>
            <li class="h-active">
        <?php endif;?>
            <i class="h-icon-circle"></i>
            <p class="h-text">发起维修申请</p>
            <p class="h-text-2"><?=date('Y-m-d H:i',$model['add_time'])?></p>
        </li>
    </ul>

</div>
<dib class="h-block-30">&nbsp;</dib>
<?php
    // 输出评价的按钮
    echo $btn;

\app\components\WxjsapiWidget::widget([
    'wx_id'=>$model['wx_id'],
    'apiList'=>['previewImage'],
    'jsReady'=>'
         document.querySelector("#previewImage").onclick = function () {
            wx.previewImage({
              current: "'.$model["fault_cover"].'",
              urls: '.json_encode($model['cover_images']).'
            });
      };
      '
]);
?>