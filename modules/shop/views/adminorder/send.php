<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\modules\shop\models\Shop;
$this->title = '待发货订单';
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
        <li><a href="<?=Url::toRoute(['list'])?>" >订单列表</a></li>
        <li><a href="<?=Url::toRoute(['check'])?>">待审核订单</a></li>
        <li class="active"><a href="javascript:void(0)" >待发货订单</a></li>
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
            'headerOptions'=>['style'=>'width:180px'],
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
        [
            'label'=>'微信资料',
            'format'=>'html',
            'value'=>function($model)
            {
                if( isset($model->user)){
                    return '<ul class="order-list-m"><li><img class="order-cover" src="'.substr($model->user->headimgurl,0,-1).'46"><span>'.$model->user->nickname.'</span>
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
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:100px'],
            'template' => '{pass} &nbsp; {unpass}',
            'buttons' => [
                'unpass' => function($url,$model,$key){
                    return Html::a('发货','#',[
                        'key-id'=>$key,
                        'class'=>'btn btn-info btn-md close-model'
                    ]);
                },
            ]
        ]
    ],
]);


/*
 * 取消任务 模态框
 */
Modal::begin([
    'header' => '发货',
    'id' => 'my-modal-cancel',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">确定发货</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::dropDownList('express_type','',Shop::getExpress(),['class'=>'form-control','id'=>'express_type']);
echo '<br/>';
echo Html::textInput('express_no','',['placeholder'=>'订单编号','class'=>'form-control','id'=>'express_no']);
echo Html::endForm();

Modal::end();

?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var keyId;
        var rowTr;
        $('#fix-list .close-model').click(function(){
            keyId = $(this).attr('key-id');
            rowTr = $(this).closest('tr');
            $('#my-modal-cancel').modal('show');
            return false;
        });

        $('#cancel-btn').click(function(){
            var text = $.trim($('#express_no').val());
            var expressType = $('#express_type').val();
            if(expressType !='0' && !text) {
                $('#cancel-status').text('请输入快递单号内容！');
                $('#express_no').focus();
                return false;
            }

            $.post(
                '<?=Url::toRoute(['ajax'])?>?order_id='+keyId,
                {type:expressType,no:text},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        rowTr.remove();
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