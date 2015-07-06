<?php
    $this->title = '订单资料';
use yii\helpers\Url;
?>
<style>
/*会员中心-我的车库*/
.u_member .m_car_c,.mf_ico{display:block;height:1.5em;width:1.5em;border-radius:0.75em;text-align:center;line-height:1.5em;background:#f0f0f0;border:1px #d7d7d7 solid;}
.u_member .default_car,.mf_xz_ico{color:#fff;background:#b10000 url(../mp/web_btn.png) 0.2em  0.4em no-repeat;background-size:6em 6em;border:none;overflow:hidden;}
/*我的车库-编辑&新增收货地址*/
.Addr_info{width:100%;}
.m_u_addr{width:100%;padding:0 5%;font-size:0.875em;}
.addr_info{overflow:hidden;margin-top:1.5em;}
.Addr_i{overflow:hidden;padding-right:3em;width:100%;}
.addr_info span{float:left;height:2.5em;line-height:2.5em;color:#b6b6b6;}
.addr_info input,.addr_info select,.addr_info textarea{height:2.5em;padding:0.5em;width:70%;float:right;}
.addr_info textarea{height:6em;}
.addr_btn{border: 1px #d7d7d7 solid;border-radius: 6px;height: 3em;
    line-height: 3em;text-align: center;margin:3em 0;background:#f3f3f3;}
/*收货地址-现金券*/
.m_coupon{border:1px #d7d7d7 solid;background:#f3f3f3;padding:0.5em;margin-top:1em;}
.m_coupon_a{width:100%;border-bottom:1px #d7d7d7 solid;overflow:hidden;padding-bottom:0.5em;}
.m_coupon_a1{display:block;float:left;font-size:4em;color:#b10000;width:1.2em;height:1em;line-height:1em;text-align:center;margin-right:0.125em;}
.m_coupon_a2{display:block;overflow:hidden;margin-top:0.25em;}
.m_coupon_a2 h3{font-size:1em;}
.m_coupon_a2 p{font-size:0.75em;line-height:1.75em;}
.m_coupon_u{margin:0.5em 0;line-height:1.5em;font-size:0.75em;color:#999;}
/*现金券-订单详情*/
.m_order{width:100%;line-height:1.75em;}
.pro_info,.b_list{overflow:hidden;padding-bottom:0.5em;border-bottom:1px #d7d7d7 solid;margin-bottom:1em;}
.m_pro_t{display:block;width:100%;height:3em;overflow:hidden;}
.m_pro_np{text-align:left;}
.m_pro_np b{float:right;color:#b10000;}
.m_pro{background:#fff;}
.order_sum {width:100%;font-size:0.825em;}
.order_sum p{text-align:left;margin-bottom:0.5em;color:#999;}
.order_sum span{float:right;color:#202020;}
.order_sum b{color:#b10000;float:right;}
.order_box2{background: #fff;padding:0.75em 0.5em;font-size:0.825em;}
.order_box2 p{margin-bottom:0.5em;color:#202020;font-size:1em;}
/*订单详情-物流跟踪*/
.m_express{margin:1em;}
.m_express p{margin-bottom:1em;}
.m_express h5{color:#666;font-weight:normal;font-size:1em;line-height:1.5em;}
.express_now{color:#b10000;}
/*物流跟踪-代购*/
.m_task,.m_login{width:100%;padding:1em 0.5em;}
.task_t{line-height:2em;border-bottom:1px #d7d7d7 solid;margin-bottom:1em;}
.task_t b{padding-left:0.5em;color:#b10000;}
.add_task{height:2.5em;margin-bottom:1em;color:#999;}
.add_task a{padding:0.5em 1em;border:1px #d7d7d7 solid;border-radius:3px;display:block;width:50%;background:#f3f3f3;text-align:center;}
.task_c1{margin-bottom:1em;}
.task_c1 select{height:2.5em;line-height:2.5em;width:100%;color:#b6b6b6;}
.task_c2 input,.task_vin{width:100%;color:#b6b6b6;padding:0.5em;margin-bottom:1em;}
.task_r1,.task_r2{padding:0.5em;margin-bottom:2em;color:#b6b6b6;}
.task_r1{width:35%;margin-right:5%;}
.task_r2{width:60%;}
.task_ps{height:6em;padding:0.5em;width:100%;margin-bottom:1em;}
/*代购-登录注册*/
.m_login{font-size:0.825em;}
.login_a{width:100%;padding:1em 0.5em;margin-bottom: 1.5em;border-radius:6px;border:1px #f3f3f3 solid;}
.login_b{width:100%;padding-bottom:0.5em;border-bottom:1px #d7d7d7 solid;}
.m_login .big_btn{margin:1.5em 0;}
.login_b a{float:right;}
.login_c{margin:0 auto;width:18em;overflow:hidden;}
.login_c a{margin:1em;display:block;width:4em;height:4em;float:left;border:1px #f3f3f3 solid;border-radius:2em;background:url(../mp/login_ico.png) no-repeat;background-size:12em 4em;}
.login_c .m_qq{background-position:-0.125em -0.125em;}
.login_c .m_alipay{background-position:-4.25em -0.125em;}
.login_c .m_weibo{background-position:-8.125em -0.125em;}
.cb_rule{color:#999;}
/*登录注册-更多项目*/
.m_more{overflow:hidden;}
.m_more a{display:block;width:33.333%;float:left;height:8em;border-bottom:1px #f3f3f3 solid;border-right:1px #f3f3f3 solid;padding:1em 0;text-align:center;font-size:0.75em;color:#999;}
.m_more a span{background:url(../mp/more_btn.png) no-repeat;display:block;width:4.75em;height:3.75em;background-size:15em 15em;margin:0 auto 0.75em auto;}
.m_more .more_1{background-position:0.4em 0.4em;}
.m_more .more_2{background-position:-4.2em 0.4em;}
.m_more .more_3{background-position:-9em 0.4em;}
.m_more .more_4{background-position:0.4em -3.5em;}
.m_more .more_5{background-position:-4.2em -3.5em;}
.m_more .more_6{background-position:-9em -3.5em;}
.m_more .more_7{background-position:0.4em -7.15em;}
.m_more .more_8{background-position:-4.4em -7.15em;}
.m_more .more_9{background-position:-9em -7.15em;}
.m_more .more_10{background-position:0.4em -10.75em;}
.m_more .more_11{background-position:-4.2em -10.75em;}

/*更多项目-搜索&商品分类*/
.p_h{margin-top:1em}
.m_categorys{border-top:1px #ddd solid;margin:2em 0;}
.m_c1{border-bottom:1px #ddd solid;width:100%;font-size:1em;line-height:3em;padding-left:5%;color:#202020;position:relative;}
.m_c1 i{position:absolute;display:block;height:1em;width:1.5em;right:5%;top:1em;background:url(../mp/web_btn.png) -1.1em -1em no-repeat;background-size:6em 6em;}
.p_show i{transform:rotate(180deg);
    -ms-transform:rotate(180deg); /* IE 9 */
    -moz-transform:rotate(180deg); /* Firefox */
    -webkit-transform:rotate(180deg); /* Safari and Chrome */
    -o-transform:rotate(180deg); /* Opera */
    transition-duration:0.3s;
    -moz-transition-duration: 0.3s; /* Firefox 4 */
    -webkit-transition-duration:0.3s; /* Safari 和 Chrome */
    -o-transition-duration:0.3s; /* Opera */}
.m_c_box{width:100%;display:none;}
.m_c_box a{display:block;width:100%;line-height:3em;border-top:1px #666 solid;padding-left:5%;color:#999;}
.mc_s a{border-color:#999;}
.p_show .m_c_box{display:block;}
/*搜索&商品分类-知识库*/
.mk_nav{width:100%;overflow:hidden;}
.mk_nav2{width:1000%;height:2.5em;line-height:2.5em;border-bottom:1px #d7d7d7 solid;}
.mk_nav2 a{padding:0.5em 0.75em;font-size:1em;line-height:2.5em;}

.mk_box{padding:0 5% 5% 5%;}
.mk_box li{padding:1em 0;width:100%;border-bottom:1px #d7d7d7 solid;list-style:none;}
.mk_box h5{margin-bottom:0.5em;font-size:1em;}
.k_box_a{overflow:hidden;}
.k_box_a img{float:left;padding-right:0.5em;width:5.5em;height:3em;}
.k_box_a span{font-size:0.825em;color:#999;font-weight:normal;display:block;height:3em;line-height:1.5em;overflow:hidden;position:relative;text-overflow: ellipsis;display: -webkit-box;
    -webkit-line-clamp: 2;-webkit-box-orient: vertical;}

.swiper-container{width:100%;overflow:hidden;border-bottom:1px #d7d7d7 solid;}
.mk_nav_n{height:2.5em;}
.swiper-active-switch {background:#be3030;}
.swiper-slide{float:left;}
.mk_nav_n .swiper-wrapper{}
.mk_nav_n .swiper-slide{display:inline;font-size:1em;line-height:2.5em;text-align:center;}
.mk_nav_n .swiper-slide span{padding:0 1em;}
.mk_xz{border-bottom:3px #b10000 solid;line-height:2.5em;height:2.5em;display:block;}
.mk_title{margin-top:1em;line-height:1.5em;font-size:1.25em;}
.mk_info{font-size:0.75em;color:#b6b6b6;}
.mk_wz{margin:1.25em 0;line-height:1.5em;color:#555;}
.mk_wz img{width:80%;text-align:center;height:auto;margin:0.25em 0;}
/*服务店↓↓↓*/
.ms_box{padding:5%;}
.ms_box2{border-bottom:1px #d7d7d7 solid;}
.ms_box2 dt{line-height:2em;margin-bottom:1em;}
.ms_box2 dt b{color:#b10000;padding:0 2px;}
.ms_box2 dd{padding:0.5em 0.25em;list-style:none;border-top:1px #e1e1e1 solid;position:relative;}
.ms_box2 dd h5{margin-bottom:0.5em;font-size:1em;color:#333;}
.ms_box2 dd p{font-size:0.825em;color:#999;}
.ms_box2 i{position:absolute;
    display: block;height:1.25em;width: 1em;
    right: 5%;top:40%;background: url(../mp/web_btn.png) -1.1em -2em no-repeat;
    background-size: 6em 6em;}
.ms_map iframe{width:100%;height:90%;text-align:center;}
/*品牌库↓↓↓*/
.m_brand{overflow:hidden;width:100%;border-top:1px #f0f0f0 solid;border-left:1px #f0f0f0 solid;margin-top:1em;}
.m_brand li{width:50%;float:left;border-bottom:1px #f0f0f0 solid;border-right:1px #f0f0f0 solid;height:5em;text-align:center;}
.m_brand img{width:7.5em;height:2.5em;}
.mb_logo img{margin:0.5em 0;width:4.5em;height:1.5em;}
.mb_logo span{display:inline-block;width:50%;float:left;text-align:center;font-size:1em;}

.mb_content p{text-indent:2em;margin-bottom:0.75em;}

.mb_b_img img{width:90%;max-width:600px;margin-bottom:0.25em;height:auto !important;}
.mb_content ul{margin-left:-3%;overflow:hidden;}
.mb_content ul li{width:30%;margin-left:3%;height:auto;float:left;border:none;text-align:center;font-size:0.75em;color:#999;padding:0.5em 0;}
.mb_content ul li img{width:100%;margin-bottom:0.5em;}
/*今日特卖↓↓↓*/
.m_tm_a h3{font-size:1.25em;}
.m_tm_a p{text-align:center;color:#999;font-size:0.75em;margin-top:0.25em;}
.m_tm_a b{color:#b10000;}
.m_tm_b li{width:100%;padding:0;margin-bottom:1em;background:#f6f6f8;border:none;}
.m_tm_b img{width:100%;border:2px #f6f6f8 solid;}
.m_tm_b p{text-align:left;padding:0.75em;}
.m_tm_b span{float:right;color:#b10000;}
.mtm_box_a img{width:100%;}

.mtm_car p{font-size:0.825em;color:#999;}
.mtm_car h5{font-size:1.25em;line-height:2em;border-bottom:1px #d7d7d7 solid;margin-bottom:0.75em;}
.mtm_car span{display:block;float:left;width:30%;margin-right:3%;color:#999;}
.mtm_car span select{width:100%;}

.goods_list_a span{display:block;height:75px;}
.goods_list_a span h5{height:55px;color:#666;font-weight:normal;font-size:0.825em;}
.goods_list_a img{width:80px;height:60px;float:left;margin-right:10px;}
.mtm_p b{float:left;color:#b10000;font-size:1em;}
.mtm_p em{padding:1px 3px	;margin-left:5px;background:#b10000;color:#fff;font-style:normal;}

.m_xcx h5{color:#999;}

.car_list select{height:2.5em;padding:0.5em;width:100%;}
.car_list_xj img{width:16em;}
.car_list input{width:100%;margin-top:0.25em;padding:0.25em;}

.goods_f a{-webkit-box-flex:1;display:block;width:25%;text-align:center;height:2.25em;line-height:2.25em;}

.m_spzc img{width:100%;margin-bottom:0.75em;}
.spzc_car p{font-size:0.825em;color:#999;}
.spzc_car h5{font-size: 1em;line-height: 2em;
    border-bottom: 1px #d7d7d7 solid;margin-bottom: 0.75em;}
.zn_btn input{width:60%;padding:0.5em 0.25em;font-size:0.825em;height:3em;}
.zn_btn a{padding:0 1.5em;color:#333;float:right;background:#fbfbfb;border:1px #d7d7d7 solid;text-align:center;border-radius:6px;font-size:0.825em;display:block;height:3em;line-height:3em;}

.spzc_ca a{height:3em;line-height:3em;background:#fff;}
.spzc_ca a,.spzc_cb a{font-size:0.825em;display:block;float:left;width:25%;border-right:1px #e3e3e3 solid;border-bottom:1px #e3e3e3 solid;text-align:center;}

.spzc_cb a{width:50%;float:left;padding:0.75em 0;border-color:#f0f0f0;}
.spzc_cb img{width:120px;height:60px;margin-bottom:0.75em;}

.pro_c em{background:#b10000;border-radius:3px;padding:0 0.5em;color:#fff;margin-right:0.25em;}

.pro_info_a li{padding:0.75em 0;border-bottom:1px #f3f3f3 solid;overflow:hidden;position:relative;}
.pd_price b{color:#b10000;font-size:2em;}

.pd_r b{color:#202020;}
.pd_car em,.pd_br em{position: absolute;top: 40%;right: 0em;background: url(../mp/web_btn.png) -1.4em -2.12em no-repeat;height: 0.96em;
    width: 0.72em;background-size: 6em 6em;}

.cb_line a{display:block;width:50%;border-right:1px #d7d7d7 solid;border-bottom:1px #d7d7d7 solid;float:left;font-size:1em;}

.chat span,.pd_tel span{position:relative;padding-left:1.25em;}
.chat em,.pd_tel em{display:block;position:absolute;left:-0.5em;top:-0.1em;
    background:url(../mp/web_btn.png) no-repeat;background-size: 6em 6em;
    height:1.6em;width:1.6em;font-size:1em;}
.chat em{background-position:-2.68em 0em;}
.pd_tel em{background-position:-4.24em 0em;}

.pd_br a{text-decoration:none;}
.pd_br em{right:0.75em;}
.pd_br span{display:block;width:100%;height:3em;position:relative;padding-left:1em;}
.pd_br i{color:#999;margin-left:0.25em;}

.pd_br table{font-size:0.825em;margin:0.25em auto;}
.pd_br table td{padding:0.5em 0;}

.pd_expenses select{line-height:2em;width:6em;text-align:center;padding-left:1em;}


.pl_sum i{color:#b10000;margin-left:0.25em;}

.pd_pl_t a{display:block;-webkit-box-flex:1;height:2.5em;line-height:2.5em;text-align:center;border-right:1px #ddd solid;background:#f6f6f6;}
.pd_pl_t .pl_xz{color:#b10000;}

.pl_star i{display:block;background-position:0 -3.32em;position:absolute;height:1em;width:4em;left:0;top:-0.1em;}

.pl_user i{margin-left:0.5em;}
.pl_user em{float:right;}

.spms table,.spms font,.spms span,.spms p{font-size:0.825em;line-height:1.75em;}
.spms tr td{width:50%;padding:0.25em 0;}
.spms tr img{width:100%;}
.spms img{max-width:100%;height:auto;}

.pd_scar_b a{display:block;width:50%;float:left;line-height:3em;text-decoration:none;color:#202020;}

.g_table_main p{font-size:1em;}
.g_c_parameters ul{padding:10px 10px 0 10px;overflow:hidden;}
.g_c_parameters li{width:100%;float:left;text-align:left;font-size:0.825em;}
.g_c_parameters p{padding:0 0 10px 10px;}
.g_c_parameters i{padding:0 0.9em;color:#fff;}
.goods_c_s img{height:auto !important;}
.goods_c_s p,.goods_c_s img{margin-bottom:6px;}
.g_table_main table{margin-bottom:12px;}
.g_table_l p{text-indent: 2em;margin-bottom:0.5em;}
.g_table_l img{display:block;}


.sc_edit{position:relative;}
.sc_edit span{top:-2.25em;}
.pro_num{float:left;}
.pro_num input{width:3em;float:left;padding:0.25em 0;text-align:center;font-size:1em;}
.pro_num a{display:block;height:2em;width:2em;line-height:2em;text-align:center;border:1px #d7d7d7 solid;float:left;font-size:1em;}
.scar_price{float:right;color:#b10000;line-height:2em;}
.ms_car{height:auto;}
.ms_car span{height:96px;}
.scar_sy,.scar_bsy{overflow:hidden;width:100%;text-align:center;font-size:0.75em;padding:0.25em 0;background:#f3f3f3;}
.scar_sy{color:#999;}
.scar_bsy{color:#b10000;}
.scar_total{margin:1em 0;text-align:right;}
.scar_total b{color:#b10000;margin-left:1em;}
.scar_b_btn{overflow:hidden;}
.scar_b_btn a{display:block;float:right;width:50%;text-align:center;background:#b10000;height:2.5em;line-height:2.5em;color:#fff;}
.scar_del{height:1.25em;color:#b10000;}
.scar_del i{float:right;padding:0.25em 1em;}
/*结算↓↓↓*/
.m_balance{padding:2%;}
.mb_addr{width:100%;height:2.5em;line-height:2.5em;text-align:center;border:1px #f3f3f3 solid;margin-top:1em;color:#999;}
.mb_pay{margin-top:1em;font-size:0.825em;color:#999;overflow:hidden;}
.mb_pay a{display:block;width:32%;margin-right:2%;border:1px #f3f3f3 solid;float:left;text-align:center;padding:0.75em 0;position:relative;}
.mb_pay .pay_xz{color:#83cf53;border-color:#83cf53;}
.pay_xz .pay_t{display:block;}
.pay_t{position:absolute;right:-1px;bottom:0;height:1.5em;width:1.5em;display:none;background:url(../mp/xz_btn.png) 0 0 no-repeat;background-size:1.5em 1.5em;}
.b_list{margin-top:1em;}
.b_list .b_list_a img{width:80px;height:60px;}
.m_balance .order_sum{background:#f3f3f3;padding-top:0.5em;}
.m_balance .order_sum p{padding:0 1em;}
.b_list_price{border-top:1px #d7d7d7 dashed;line-height:4em;}
.b_list_price b{font-size:1.5em;}
.big_btn2{margin:1em 0;}

.mb_jf input{width:4em;text-align:left;margin-left:0.25em;padding:0.25em;}
.mb_jf span{font-size:0.825em;color:#666;}

.mb_coupon label{width:100%;display:block;margin-top:0.75em;color:#999;}

.m_balance .m_u_member{margin:1em 0;}
    .pay_t {
        background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJ9QTFRF////hdBWktVn9fvxx+mxott91u/GkNRlg89UwOenyuq17Pjl5/be+/35j9Rj5vXcqd6Ih9FZzOu4q9+L1O7E3PLP9fvyrN+M9/z08/rv7vjnhtBYlNVq7fjlhM9UveWjlNZrltZtuOSd2/HM5fXbj9RkidFchtBX7/npo9uA0O2+2/HNruCO0u3Andl3pdyCo9t//P779Pvvg89T////2g/0RgAAADV0Uk5T/////////////////////////////////////////////////////////////////////wB8tdAKAAAAtElEQVR42oTRxw7CMBAEUJtAEnrvvfc6/P+3EQEx9lpifLC0mnfYsdWTHEVyEAACQAAIAAEgAASAABAAAkAACAABIAAE2GFu4AM7v6mFB+w802lpCey83g69HZztwupEAuhfHE97a9kCqDW2KeiW57JmMoxUNi14P8l3eE+VoPkpYKQB33GvrskdlQ5DAcxy+X4EHFfyqa12hZ0+jzcCOP2LwWMmPkv83/ISuwB/jyI5XgIMAFaGxqTZed1+AAAAAElFTkSuQmCC) 0 0 no-repeat;
        background-size: 1.5em 1.5em;
    }

.mbalance_t {
    border-bottom: 1px #202020 solid;
    margin-top: 1em;
    line-height: 2em;
}
</style>

<div class="m_balance">
    <div class="mbalance_t">收货人信息</div>
    <?php if($address):?>
        <div id="order_address" style="display: block;"><div class="m_u_member address-select"><a class="u_member"><span class="u_member_b Addr_i"><?php echo $address['user_name'],' &nbsp;',$address['phone'];?><p></p><p><?php echo $address['province'],$address['city'],$address['area'],'&nbsp;&nbsp;',$address['address'];?></p></span><em class="m_car_c default_car" id="address-select"></em></a><a indepth="true" class="member_edit_a" href="/address/update/<?php echo $address['id'];?>" target="_top">编辑地址</a><a indepth="true" class="member_edit_b" href="/address/create?url=/order/index" target="_top">+添加新地址</a></div></div>
    <?php else:?>
        <div id="order_address">
            <A href="/address/create?url=/order/index" ><div class="mb_addr" target="_blank">+添加新地址</div></A>
        </div>
    <?php endif;?>

    <div class="mbalance_t">支付方式</div>
    <div class="mb_pay">
        <a class="pay-type pay_xz" data-type="1" data-freight="" id='pay1'>货到付款<br>(运费8元)<i class="pay_t"  ></i></a>
        <a class="pay-type" data-type="3" data-freight="0" id='pay2'>在线支付<br>免运费<i class="pay_t" ></i></a>
        <a class="pay-type" data-type="2" data-freight="0" style="margin-right:0;" id='pay3'>上门自取<br>免运费<br><!-- (运费123元) --><i class="pay_t"></i></a>

    </div>
    <p style="color:red;margin-top:0.5em;display:none" id='ziti'>注:上门自取地址为：广州市广园东路1881号B栋4楼B03   电话：4000-228-168</p>

    <script>
        function refresh_money(){
            //console.log(typeof Number($('#goods_money').html().replace(/,/g, "")));
            console.log($('#goods_money').html()+'vv'+$('#freight').html()+'vv'+$('#youhuiquan').html());
            $('#all_money').html((Number($('#goods_money').html().replace(/,/g, ""))+Number($('#freight').html().replace(/,/g, ""))-Number($('#youhuiquan').html().replace(/,/g, ""))).toFixed(2));

        }
    </script>

    <div class="mbalance_t">商品清单</div>
    <!--订单-->
    <div id='cart_lists'>
        <?php foreach($model as $i):?>
            <div class="b_list">
            <span class="b_list_a">
            <img src="<?php echo $i['cover'];?>"></span>
            <span class="b_list_b">
            <p class="m_pro_t"><?php echo $i['name'];?></p>
            <p class="m_pro_np">
                共<?php echo $i['item_nums'];?>件 <b>￥<?php echo number_format($i['item_nums']*$i['price'],2,'.','');?></b>
            </p>
            </span>
            </div>
        <?php endforeach;?>

    </div>

    <div class="order_sum">
        <p>商品金额<span>￥<span id="goods_money"></span></span></p>
        <p>运费<span id="freight_box">￥<span id="freight" ></span></span></p>
        <span id="youhuiquan"></span><!-- <p>现金券/优惠码<span>￥<span id="youhuiquan">0.00</span></span></p> --><!-- -￥10.00 -->
        <p>赠送积分<span  id="jifen"></span></p>
        <p class="b_list_price">订单总额<b>￥<span id="all_money" style="color:#b10000">0.00</span></b></p>
    </div>

    <a href="<?=Url::toRoute(['/shop/order/put'])?>" class="h-button">提交订单</a>




    <FORM METHOD=POST ACTION="">
        <INPUT TYPE="hidden" NAME="address" id="form_address" >
        <INPUT TYPE="hidden" NAME="payment"  id="form_payment" value="6">
        <INPUT TYPE="hidden" NAME="form_money_all"  id="form_money_all" >
    </FORM>
</div>
