<?php
use yii\helpers\Url;
$this->title = '机器页面';
?>


<div class="h-center-wrap">

    <?=$btnHtml?>

    <a class="h-link" href="<?=Url::toRoute(['m/taskdetail','id'=>$service_id])?>">
        故障信息
    </a>
    <a class="h-link" href="<?=Url::toRoute(['m/task','id'=>$wid,'type'=>'history'])?>">
        维修记录
    </a>

    <a class="h-link" href="<?=Url::toRoute(['mcharge/add','id'=>$wid,'machine_id'=>$mid])?>">
        收租录入
    </a>
</div>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var hasClick = 0;
        $(function(){
            $('.process-btn').click(function(){
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
?>