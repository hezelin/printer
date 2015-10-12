<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\grid\CheckboxColumn;
use app\modules\shop\models\Shop;
$this->title = '订单管理';
?>
<style>

    .order-list-m,.order-list,.order-address{
        list-style: none;
        padding: 0; margin: 0;
        font-size: 14px;
    }
    .order-list-m li{
        height: 50px; width: 180px; border-bottom: 1px solid #cccccc; padding: 7px 0; margin-bottom: 3px;
    }
    .width-150 li{ width: 150px;}
    .order-list-m img{
        height: 40px; width: 40px; margin-right: 10px;
        float: left;
    }
    .order-list-m b{ color: #b10000; float: right; font-weight: 500;}
    .order-list li{
        height: 24px;
        line-height: 24px;
    }
    .order-name{ overflow: hidden; width: 130px; height: 20px;  float: left;}
    .order-error{
        display: inline-block;
        color: #ff0000;
    }
    .order-address li{
        line-height: 24px;
    }
    .order-remark{
        color: #1e90ff;
    }
</style>
<div >
    <ul class="nav nav-tabs" >
        <li class="active"><a href="javascript:void(0)" >所有订单</a></li>
        <li><a href="<?=Url::toRoute(['check'])?>" >待审核订单</a></li>
        <li><a href="<?=Url::toRoute(['send'])?>" >待发货订单</a></li>
    </ul>
</div>
<p>&nbsp;</p>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'id' => 'fix-list',
    'columns' => [
        [
            'class' => CheckboxColumn::className(),
        ],
        'order_id',
        [
            'format'=>'html',
            'label'=>'订单数据',
            'value'=>function($model)
            {
                $item = json_decode($model->order_data,true);
                $html = [];
                foreach($item as $i){
                        $html[] = '<li><a href="/shop/backend/view?id='.$i['item_id'].'"><img class="order-cover" src="'.$i['cover'].'"><span class="order-name">'.$i['name'].'</span>
                            <p class="order-num-price">'.$i['item_nums'].' 件 <b>¥'.number_format($i['item_nums']*$i['price'],2,'.','').'</b></p></li>';
                }
                return '<ul class="order-list-m">'.join('',$html).'</ul>';
            }
        ],
        [
            'headerOptions'=>['style'=>'width:200px'],
            'format'=>'html',
            'label'=>'付款情况',
            'value'=>function($model)
            {
                $html[] = '<p>支付金额: <span style="color:#b10000;font-weight: 600"> ¥'.$model->total_price.'</span></p>';
                $html[] = '<p>积分支付: <span> '.$model->pay_score.'</span></p>';
                $html[] = '<p>其中运费: <span> ¥'.$model->freight.'</span></p>';

                return '<ul class="order-list"><li>'.join('</li><li>',$html).'</li></ul>';
            }
        ],
//        'remark',
        [
            'label'=>'微信资料',
            'format'=>'html',
            'value'=>function($model)
            {
                if( isset($model->user)){
                    return '<ul class="order-list-m width-150"><li><img class="order-cover" src="'.substr($model->user->headimgurl,0,-1).'46"><span>'.$model->user->nickname.'</span>
                            <p>'.$model->user->province.$model->user->city.'</p></ul>';
                }
                return '丢失';
            }
        ],
        [
            'label'=>'收件资料',
            'format'=>'html',
            'headerOptions'=>['style'=>'width:180px'],
            'value'=>function($model)
            {
                if($model->remark)
                    $word = Html::tag('li','留言:'.$model->remark,['class'=>'order-remark']);
                else $word = '';
                if( isset($model->address))
                {
                    return '<ul class="order-address"><li>'.$model->address->name.','.$model->address->phone.'</li>
                    <li>'.$model->address->city.$model->address->address.'</li>'.$word.'</ul>';
                }
                return '无';
            }
        ],
        [
            'attribute'=>'order_status',
            'filter'=>Shop::getOrderStatus(),
            'format'=>'html',
            'value'=>function($model){
                $status = Shop::getOrderStatus($model->order_status);
                if($model->order_status == 2)
                    return $status.Html::tag('div',$model->check_word,['class'=>'order-error']);
                else if($model->order_status == 6){
                    if($model->express == 0)
                        return $status.Html::tag('div','业务员派送',['class'=>'text-danger']);
                    return $status.Html::a('查看'.Shop::getExpress($model->express),Url::toRoute(['express','type'=>$model->express,'no'=>$model->express_num]),['class'=>'btn btn-info btn-sm']);
                }else if($model->order_status ==9){
                    return $status.Html::tag('div',$model->check_word,['class'=>'order-error']);
                }
                return $status;
            }
        ],
        [
            'attribute'=>'pay_status',
            'filter'=>Shop::getPayStatus(),
            'value'=>function($model){
                return Shop::getPayStatus($model->pay_status);
            }
        ],
        [
            'attribute'=>'add_time',
            'label'=>'创建时间',
            'format'=>['date','php:Y-m-d H:i'],
        ]
    ],
]);
?>
    <button id="btn-unpass" type="button" class="btn btn-danger" style="margin-left: 15px;">批量取消订单</button>
<?php


/*
 * 取消任务 模态框
 */
Modal::begin([
    'header' => '取消原因',
    'id' => 'my-modal-cancel',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">提交</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'不超过200字','class'=>'form-control','id'=>'cancel-text']);
echo Html::endForm();

Modal::end();

?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var orderIds;
//        批量取消订单
        $('#btn-unpass').click(function(){
            orderIds = [];
            orderIds = $('#fix-list').yiiGridView('getSelectedRows');

            if(orderIds.length > 0){
                $('#my-modal-cancel').modal('show');
            }else{
                alert("请选择订单");
            }
        })

        $('#cancel-btn').click(function(){
            var text = $.trim($('#cancel-text').val());
            if(!text){
                $('#cancel-status').text('请输入取消原因！');
                $('#cancel-text').focus();
                return false;
            }
            $.post(
                '<?=Url::toRoute(['cancel'])?>',
                {'text':text,'data[]':orderIds},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        location.reload();
                    }
                    else
                        alert(res.msg);
                },'json'
            );
        });

        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>