<?php
    $this->title='添加地址';
?>

<style>
.tbl_address_row{height: 36px; line-height: 36px; width: 80%; margin: 15px 10% 0 10%; padding: 0 10px; color: #444444;
border-radius: 4px; text-align: left; border: 1px solid #cccccc; font-size: 16px;}
    .tbl_address_area{
        height: 90px; line-height: 30px;width: 80%; margin: 15px 10% 0 10%; padding: 0 10px; color: #444444;
        border-radius: 4px; text-align: left; border: 1px solid #cccccc; font-size: 16px;
    }
</style>

    <form id="tbl_address_form" method="post" action="<?=\yii\helpers\Url::toRoute(['add','id'=>$id,'url'=>Yii::$app->request->get('url')])?>">
        <input type="hidden" id="tbl_address_address_id" name="TblShopAddress[address_id]" />
        <input type="hidden" id="tbl_address_city" name="TblShopAddress[city]" />
        <input type="hidden"  name="TblShopAddress[openid]" value="<?=$openid?>" />
        <input class="tbl_address_row" type="text" id="tbl_address_name" name="TblShopAddress[name]" placeholder="姓名"/>
        <input class="tbl_address_row" type="text" id="tbl_address_phone" name="TblShopAddress[phone]" placeholder="手机"/>

        <select class="tbl_address_row" id="province"  name="province">
            <option value="0" selected>选择省市</option><option value="11">北京</option><option value="12">天津</option><option value="13">河北</option><option value="14">山西</option>
            <option value="15">内蒙古</option><option value="21">辽宁</option><option value="22">吉林</option><option value="23">黑龙江</option><option value="31">上海</option>
            <option value="32">江苏</option><option value="33">浙江</option><option value="34">安徽</option><option value="35">福建</option><option value="36">江西</option>
            <option value="37">山东</option><option value="41">河南</option><option value="42">湖北</option><option value="43">湖南</option><option value="44">广东</option>
            <option value="45">广西</option><option value="46">海南</option><option value="50">重庆</option><option value="51">四川</option><option value="52">贵州</option>
            <option value="53">云南</option><option value="54">西藏</option><option value="61">陕西</option><option value="62">甘肃</option><option value="63">青海</option>
            <option value="64">宁夏</option><option value="65">新疆</option><option value="71">台湾</option><option value="81">香港</option><option value="91">澳门</option>
        </select>

        <select class="tbl_address_row" id="city" name="city">
                <option value="0">请选择城市</option>
        </select>

        <select class="tbl_address_row" id="region" name="region" >
                <option value="0">请选择区/县</option>
        </select>

        <textarea class="tbl_address_area" id="tbl_address_area" name="TblShopAddress[address]" placeholder="街道地址"></textarea>

        <div class="form-error">
        <?php
        if( Yii::$app->session->hasFlash('error') )
            echo Yii::$app->session->getFlash('error');
        ?>
        </div>
        <button type="button" id="address-save" class="h-button">保存</button>
    </form>

<script>
    <?php $this->beginBlock('JS_END') ?>
    // 验证提交数据
    var isMobile=/^(?:13\d|15\d|18\d|17\d)\d{5}(\d{3}|\*{3})$/; //手机号码验证规则
    var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
    $(function(){
        // 提交资料
        $('#address-save').click(function(){
            if( !$.trim($('#tbl_address_name').val()) ) { alert('请填写收件人'); return false;}
            var phone = $.trim($('#tbl_address_phone').val());
            if( !phone ) { alert('请填写手机号码'); return false;}
            if( !isMobile.test(phone)){ alert('手机号码格式错误'); return false;}
            if( !$.trim($('#tbl_address_area').val()) ) { alert('请填写街道地址'); return false;}
            if( !$.trim($('#region').val()) || $('#region').val() =='0' ) { alert('请选择区域'); return false; }

            $('#tbl_address_address_id').val( $('#region').val() );
            $('#tbl_address_city').val( $("#city option").not(function(){ return !this.selected }).text() + $("#region option").not(function(){ return !this.selected }).text())
            $('#tbl_address_form').submit();
        });

        // 城市地址联动
        $('#province').change(function(){
            var provinceId = $(this).val();
            if( provinceId == '0' ) return false;
            $.getJSON('/shop/address/ajax?id='+provinceId,function(result){
                if( result.status == 1){
                    $('#city').html( result.data);
                }
            });
        });

        $('#city').change(function(){
            var cityId = $(this).val();
            if( cityId == '0') return false;
            $.getJSON('/shop/address/ajax?id='+cityId,function(result){
                if( result.status == 1){
                    $('#region').html( result.data);
                }
            });
        });

        $('#region').change(function(){
            var regionId = $(this).val();
            if( regionId == '0') return false;
            $('#address-id').val( regionId );
        });
    });
    <?php $this->endBlock();?>
</script>


<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>