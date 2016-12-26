<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\grid\CheckboxColumn;
use app\modules\shop\models\Shop;
$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
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
        /*display: inline-block;
        //height: 24px;
        line-height: 24px;*/
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
    .close-model{ margin-top: 10px;}
    p{
        margin: 0 0 0px;
    }
</style>
<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['list'])?>" >所有订单</a></li>
        <li class="active"><a href="javascript:void(0)" >待审核订单</a></li>
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
            'checkboxOptions' => function($model, $key, $index, $column) {
                return ['value' => $model->order_id.'|'.$model->pay_status];
            }
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
                        $html[] = '<li>
<a href="/shop/backend/view?id='.$i['item_id'].'">
<img class="order-cover" src="'.$i['cover'].'">
<span class="order-name">'.$i['name'].'</span>
<p class="order-num-price">'.$i['item_nums'].' 件 <b>¥'.number_format($i['item_nums']*$i['price'],2,'.','').'</b></p>
</a>
</li>';
                }
                return '<ul class="order-list-m">'.join("\n",$html).'</ul>';
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
                'pass' => function($url,$model,$key){
                    return Html::a('通过','#',[
                        'key-id'=>$key,
                        'pay-status'=>$model->pay_status,
                        'class'=>'btn btn-info btn-sm click-pass'
                    ]);
                },
                'unpass' => function($url,$model,$key){
                    return Html::a('不通过','#',[
                        'title'=>'配件备注',
                        'key-id'=>$key,
                        'class'=>'btn btn-danger btn-sm close-model'
                    ]);
                },
            ]
        ]
    ],
]);
?>
    <button id="btn-pass" type="button" class="btn btn-info">批量通过</button>
    <button id="btn-unpass" type="button" class="btn btn-danger" style="margin-left: 15px;">批量不通过</button>

<?php
/*
 * 取消任务 模态框
 */
Modal::begin([
    'header' => '不通过原因',
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
        var orderTr;
        var opera;      // 1 or 0

        function orderPass(){
            $.post(
                '<?=Url::toRoute(['pass'])?>',
                {'data[]':orderIds},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        if(opera == 0){
                            orderTr.remove();
                            orderIds = [];
                        }
                        else{
                            location.reload();
                        }
                    }
                    else
                        alert(res.msg);
                },'json'
            );
        }

        $('#fix-list .close-model').click(function(){
            orderIds = [];
            orderIds.push( $(this).attr('key-id') );
            orderTr = $(this).closest('tr');
            opera = 0;
            $('#my-modal-cancel').modal('show');
            return false;
        });
        $('#fix-list .click-pass').click(function(){
            orderIds = [];
            orderIds.push( $(this).attr('key-id') + '|' + $(this).attr('pay-status') );
            orderTr = $(this).closest('tr');
            opera = 0;
            orderPass();
            return false;
        });

        $('#cancel-btn').click(function(){
            var text = $.trim($('#cancel-text').val());
            if(!text){
                $('#cancel-status').text('请输入不通过原因！');
                $('#cancel-text').focus();
                return false;
            }
            $.post(
                '<?=Url::toRoute(['unpass'])?>',
                {'text':text,'data[]':orderIds},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        if(opera == 0){
                            orderIds = [];
                            orderTr.remove();
                        }
                        else{
                            location.reload();
                        }
                    }
                    else
                        alert(res.msg);
                },'json'
            );
        });
//        批量不通过
        $('#btn-unpass').click(function(){
            orderIds = [];
            orderIds = $('#fix-list').yiiGridView('getSelectedRows');

            if(orderIds.length > 0){
                opera = 1;
                $('#my-modal-cancel').modal('show');
            }else{
                alert("请选择订单");
            }
        })
//        批量通过
        $('#btn-pass').click(function(){
            orderIds = [];
            $('#fix-list').find("input[name='selection[]']:checked").each(function () {
                orderIds.push($(this).val());
            });

            if(orderIds.length > 0){
                orderPass();
            }else{
                alert("请选择订单");
            }
        })
        <?php $this->endBlock();?>

    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>