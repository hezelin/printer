<?php
use yii\helpers\Url;
    $this->title='我的地址';
?>

<style>
    #address-view{
        margin: 0 5%;
        font-size: 16px;
    }
    #address-list li{
        font-size: 14px; color: #444;
        border-bottom: 1px solid #cccccc;
        padding:15px 0;
        position: relative;
    }
    .address-name{
        height: 30px; line-height: 30px;
        font-size: 18px;
    }
    .address-detail{
        height: 24px; line-height: 24px;
    }
    .address-title {
        border-bottom: 1px #202020 solid;
        margin-top: 1em;
        line-height: 2em;
    }
    .address-add-btn{
        border: 1px solid #ccc;
        color: #333;
        background-color: #fff;
        margin: 30px auto 15px;
        height: 40px;
        line-height:40px;
        text-align: center;
        display: block;
        font-size: 16px;
        border-radius: 4px;
    }
    .icon-select{
        background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjlGNTAxRTgwMjNCNTExRTU4NzAxQjZBRkI3QUMzQTUzIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjlGNTAxRTgxMjNCNTExRTU4NzAxQjZBRkI3QUMzQTUzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OUY1MDFFN0UyM0I1MTFFNTg3MDFCNkFGQjdBQzNBNTMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OUY1MDFFN0YyM0I1MTFFNTg3MDFCNkFGQjdBQzNBNTMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5zPQiQAAACf0lEQVR42mJ0aznPQCRgBmJXKLaB8g2gcheA+C8QHwHi3VD8lxhDWYhQIwHEjUAcDsT8ONQYQ2kzIC4C4o9AvBKI64H4BT7DmfDIcQJxCxDfBuI0PJZjA/xQPbehZnCS6gCQrw8AcTUQ8zCQD3igZhyAmkmUA0DxehIanNQCILPOIKUZnA4AuXILEMsxUB9IQ82WwOUANiDeCFVIKyANtYMNmwPqqBzs+KKjDt0BIJflM9AP5MNCGuaACgpTO07gqC3I0B6lzMDOwoSeOypgDgBxkmlleZm/HIORIi+DlTpGMQKykwfkAD98BQWlljMxMjLM2/+cYf/V99gKOj+QA+xpbfnKYy9xKbUHOUB3gCwHAV2QA1RwltNAQ0R4WWllOQioMOGqZECGFPrIMkxJUmOQEWanheXgSgtnbcjKzMggIcDGIMjDytATo4LXEWRaDi+IPmKT+PnnH0PN8nsMlx59wesISiwH2Q1ywB1csoQcQaHlIHAH5IDL+FTgcgQVLAeBy4zANmEUkLGUkEpQUdoSqcSgJ8fD8OXHXwYudiZKLQeBaFAIbALi74RUIocEDwcz2PJFh15QYjnIzk0gB3wB4gXE6IA74uEXhq3n3jIsPfyCkjJrLshuRmizHFQ13qBVjYgFgDytAcRPYeXAUyCeSMf2wESonSgtolYgvkYHy69B7cJokoEShT8Qv6ah5a+hdnzH1SoGFUqesOChMngKNfsOoX7BWSA2AeJTVLT8FNTMs8T2jED5ywGIO4gpIwjk9Q6oWS9I7RuCNFcCsRIQz8JVaeGqZKB6lKBmfKekdwxyeToQ50K75h5AbImje34ciHdAu+e/iHEpQIABACBJ0hXr/zVUAAAAAElFTkSuQmCC) no-repeat center center;
        width: 32px;
        height: 32px;
        position: absolute;
        top: 30%;
        right: 10px;
        display: none;;
    }
    #address-list li.has-active .icon-select{ display: block;}
    #pd_scar{height:100%;width:100%; display: none; top:0; left:0; position:fixed;z-index:599999;color:#202020;text-align:center;font-size:16px;}
    .pd_scar2{position:absolute;top:40%;width:80%;left:10%;background:#fff;z-index:3;border-radius:6px;}
    .pd_scar2 a{height: 50px; line-height: 50px; width: 100%; display: block; color: #4684cf;}
    .btn-select{ border-bottom: 1px solid #cccccc;}
    .pd_bg{float:left;height:100%;width:100%;background:#000;opacity:0.5;}
</style>

<div id="address-view">
    <div class="address-title">地址列表</div>
    <ul id='address-list'>
        <?php foreach($model as $k=>$i):?>
            <li data-id="<?=$i['id']?>" class="<?= ($k==0)? 'has-active':''?>">
                <h3 class="address-name"><?=$i['name'],',',$i['phone']?></h3>
                <p class="address-detail">
                    <?=$i['city'],',',$i['address']?>
                </p>
                <i class="icon-select">&nbsp;</i>
            </li>
        <?php endforeach;?>
    </ul>
    <a href="<?=Url::toRoute(['/shop/address/add','id'=>$id,'url'=>Yii::$app->request->get('url')])?>" class="address-add-btn">+添加地址</a>
</div>


    <div id="pd_scar">
        <div class="pd_scar2">
            <a class="btn-select">选择地址</a>
            <a class="btn-update">编辑地址</a>
        </div>
        <div class="pd_bg pd-hide"></div>
    </div>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var addressId = 0;
    var selectClick = 0;
    var $this;
    var fromUrl = '<?=Yii::$app->request->get('url')?>';
    $(function(){
        $('#address-list li').click(function(){
            addressId = $(this).attr('data-id');
            $this = $(this);
            $('#pd_scar').show();
        })
        $('.pd-hide').click(function(){
            $('#pd_scar').hide();
        });
        $('.btn-select').click(function(){
            if(selectClick == 1) return false;
            if($this.hasClass('has-active')){
                $('#pd_scar').hide();
                return false;
            }

            selectClick = 1;
            $.post(
                '<?=Url::toRoute(['/shop/address/select','id'=>$id])?>',
                {address_id:addressId},
                function(resp){
                    if(resp.status == 1){
                        $this.addClass('has-active').siblings().removeClass('has-active');
                        if( fromUrl )
                            location.href = fromUrl;
                    }else
                        alert(resp.status);
                    $('#pd_scar').hide();
                    selectClick = 0;
                },'json'
            )
        });

        $('.btn-update').click(function(){
            location.href = '<?=Url::toRoute(['/shop/address/update','id'=>$id,'url'=>Yii::$app->request->get('url')])?>&address_id='+addressId;
        });
    });
    <?php $this->endBlock();?>
</script>


<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>