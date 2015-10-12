<?php
use app\components\PassthroughWidget;
use yii\helpers\Url;
$this->title = '配件列表';
Yii::$app->params['layoutBottomHeight'] = 40;

?>
    <style>
        <?php $this->beginBlock('CSS') ?>
        .item-list{  display: block;  padding: 12px 0;  width: 90%;  margin: 0 5%;  border-bottom: 1px #e3e3e3 solid;  font-size: 16px;  }
        .item-list img{  width: 80px;  height: 60px;  float: left;  }
        .item-list .img-status{  width: 80px;  height: 80px;  float: left;  margin-right: 10px;  color: #666;  text-align: center;  }
        .item-list span h5{  line-height: 20px;  min-height: 60px;  color: #666;  font-weight: normal;  font-size: 16px;  }
        .item-list span h6{  height: 30px;  color: #666;  font-weight: normal;  font-size: 16px;  }
        .mtm_p{  text-align: right;  color: #999;  font-size: 14px;  }
        .mtm_p b{  float: left;  font-size: 16px;  font-weight: 500;  color: #b10000;  }
        .scan-btn{ display:none;}
        .fault-info{  color: #b18b8d;  }
        .bing-info{  color: #5fa3b1;  }
        .blank-scan{
            height: 80px; text-align: center; font-size: 18px; line-height: 80px; background-color: #d1eafb;
            color: #999;
        }
        <?php $this->endBlock() ?>
    </style>
<?php
$this->registerCss($this->blocks['CSS']);
?>

    <div id="item-list-wrap">
        <?php if($model):?>
            <?php foreach($model as $row):?>
                <div class="item-list">
                    <div class="img-status">
                        <img src="<?=$row['cover']?>">
                        <?=\app\modules\shop\models\Shop::getParts($row['status'])?>
                    </div>
               <span>
                   <h5>(<?=$row['category']?>)<?=$row['name']?>
                        <?php if($row['fault_id']):?>
                        <span class="fault-info">(维修任务中)</span>
                        <?php else:?>
                        <span class="bing-info">(携带申请中)</span>
                        <?php endif;?>
                   </h5>
                   <p class="mtm_p">
                       <b class="scan-btn" data-url="<?=Url::toRoute(['/shop/parts/bing','part_id'=>$row['parts_id'],'item_id'=>$row['item_id'],'id'=>$row['wx_id'],'un'=>$un,'fault_id'=>$row['fault_id'],'machine_id'=>$row['machine_id'] ])?>">扫描机器绑定</b>
                       <a class="parts-cancel-btn" parts-id="<?=$row['parts_id']?>" href="javascript:void(0)">取消</a></p>
               </span>
                </div>
            <?php endforeach;?>
        <?php endif;?>
        <div class="scan-btn blank-scan"  data-url="<?=Url::toRoute(['/shop/parts/bing','item_id'=>$item_id,'id'=>$id,'un'=>$un])?>">
            扫描绑定
        </div>
    </div>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var hasClick = 0;

        function getId(url) {
            var pos = parseInt(url.lastIndexOf("/")) + 1;
            return url.substring(pos);
        }

        function getUrlParam(name,url) {
            var reg = new RegExp("[\?|&]" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = url.match(reg);  //匹配目标参数
            if (r != null) return unescape(r[1]);
            return null; //返回参数值
        }

        $(function(){
            $('.parts-cancel-btn').click(function(){
                if(hasClick == 1) return false;
                var $this = $(this).closest('.item-list');
                var parts_id = $(this).attr('parts-id');
                $.post(
                    '<?=Url::toRoute(['cancel','id'=>$id])?>&parts_id='+parts_id,
                    function(resp){
                        if(resp.status == 1)
                            $this.remove();
                        else alert( resp.msg );
                        hasClick = 0;
                    },'json'
                );
            });

            $('.scan-btn').click(function(){
                var url = $(this).attr('data-url');
                wx.scanQRCode({
                    needResult: 1,  // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                    success: function (res) {
                        var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                        if( result.indexOf('codeapi/machine') == -1){
                            alert('二维码不合法,非机器二维码！');
                            return false;
                        }
                        var id = getId(result);
                        var machine_id = getUrlParam('machine_id',url)
                        if(  machine_id != null && machine_id != id ){
                            alert('维修任务的机器和扫描的机器不匹配');
                            return false;
                        }
                        location.href = url + '&machine_id=' + id;
                    }
                });
            });
        });
        <?php $this->endBlock();?>
    </script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

\app\components\WxjsapiWidget::widget([
    'wx_id'=>$id,
    'apiList'=>['scanQRCode'],
    'jsReady'=>'
        var ele = document.querySelectorAll(".scan-btn");
        for(var i in ele)
            ele[i].style.cssText="display:block";'
]);
?>