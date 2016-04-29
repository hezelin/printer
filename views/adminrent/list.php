<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;


$this->title = '租借列表';
?>
<style>
    .high-remark{
         color: #FF6666;
     }
    .rent-price,.rent-price li{
        padding: 0; margin: 0; list-style: none;
    }
    .rent-price li{ width: 100px; height: 20px; line-height: 20px;}
</style>
<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id'=>'my-grid',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute'=>'id',
            'header'=>'客户id',
        ],
        [
            'attribute'=>'type',
            'header'=>'机型',
            'value'=>'machine.machineModel.type',
        ],
        [
            'attribute'=>'series_id',
            'header'=>'编号',
            'headerOptions'=>['style'=>'width:100px'],
            'format'=>'html',
            'value'=>function($model) {
                if(!$model->machine) return '<span class="not-set">未设置</span>';
                return Html::a($model->machine->series_id,\yii\helpers\Url::toRoute(['machine/view','id'=>$model->machine_id]),['title'=>'查看机器详情']).
                        Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-qrcode"></i>',\yii\helpers\Url::toRoute(['code/machine','id'=>$model->machine->id]),['title'=>'查看机器二维码']);
            }
        ],
        [
            'attribute'=>'come_from',
            'header'=>'租借关系',
            'filter'=>\app\models\ConfigBase::$machineOrigin,
            'format'=>'html',
            'value'=>function($model){
                if(!$model->machine) return '<span class="not-set">未设置</span>';
                return \app\models\ConfigBase::getMachineOrigin($model->machine->come_from);
            }
        ],
        [
            'label'=>'价格',
            'format'=>'html',
            'value'=>function($model){
                $data = [
                    '月租：'.$model->monthly_rent,
                    '黑白：'.$model->black_white,
                ];
                if($model->colours)
                    array_push($data,'彩色：'.$model->colours);
                return Html::ul($data,['class'=>'rent-price']);
            }
        ],
        'phone',
        'name',
        'address',
        [
            'attribute' => 'due_time',
            'format' => ['date', 'php:Y-m-d'],
        ],
        [
            'attribute' => 'add_time',
            'format' => ['date', 'php:Y-m-d'],
        ],
        [
            'attribute' => 'first_rent_time',
            'label'=>'下次收租',
            'value'=>function($model){
                if($model->first_rent_time < 1000000000)
                    return '无';
                return date('Y-m-d',$model->first_rent_time);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:160px'],
            'template' => '{fault} &nbsp; {update} &nbsp; {map} &nbsp; {charge} &nbsp; {delete} &nbsp; {rental}',
            'buttons' => [
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',$url,['title'=>'修改']);
                },
                'map' => function($url,$model,$key){
                    if((int)$model->latitude && (int)$model->longitude )
                        return Html::a('<span class="glyphicon glyphicon-map-marker"></span>',$url,['title'=>'定位']);
                    return Html::a('<span class="glyphicon glyphicon-map-marker"></span>',$url,['title'=>'定位','class'=>'high-remark']);
                },
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>',$url,[
                        'title'=>'删除关系',
                        'data-method'=>'post',
                        'data-confirm'=>'确定删除吗？',
                        'data-pjax'=>0
                    ]);
                },
                'fault' => function($url,$model,$key){
                    if(isset($model->machineFault2->status) && $model->machineFault2->status < 8)
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',Url::toRoute(['service/process','id'=>$model->machineFault2->id]),
                            [
                                'title'=>'查看维修进度',
                            ]);

                    return Html::a('<i class="glyphicon glyphicon-wrench"></i>',$url,
                        [
                            'title'=>'电话维修录入',
                            'class'=>'my-grid-model',
                            'data-machine-id'=>$model->machine_id,
                            'data-openid'=>$model->openid
                    ]);
                },
                'charge' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-yen"></span>',Url::toRoute(['charge/add','machine_id'=>$model->machine_id]),['title'=>'收租录入']);
                },
                'rental' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-stats"></span>',Url::toRoute(['/charts/machine-rental','machine_id'=>$model->machine_id]),['title'=>'租金统计']);
                }
            ]
        ]

    ],
]);

?>
<?php
    /*
    * 取消租借 模态框
    */
    Modal::begin([
    'header' => '故障原因',
    'id' => 'my-modal-cancel',
    'size' => 'my-grid-modal',
    'toggleButton' => false,
    'footer' => '
    <button id="go-back" type="button" class="btn btn-default">上一步</button>
    <button id="server-sub" type="button" class="btn btn-success">保存维修</button>
    <button id="cancel-btn" type="button" class="btn btn-primary">任务分配</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
    ]);
    echo Html::beginForm('','',['class'=>'form-horizontal','id'=>'fault-text-form']);
    echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);

    echo Html::textarea('fault_text','',['placeholder'=>'故障原因','class'=>'form-control','id'=>'cancel-text']);
    echo '<br/>';
    echo Html::dropDownList('fault_type','',\app\models\ConfigBase::$faultStatus,['class'=>'form-control','id'=>'fault-reason']);
    echo '<br/>';
    echo Html::textarea('fault_remark','',['placeholder'=>'备注留言','class'=>'form-control','id'=>'fault-remark']);

    echo Html::endForm();

echo GridView::widget([
    'dataProvider'=> $fixProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'id'=>'my-fix-model',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'name',
        'phone',
        [
            'attribute'=>'wait_repair_count',
            'contentOptions'=>['class'=>'repair-count'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '分配',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{select}',
            'buttons' => [
                'select'=>function($url,$model,$key){
                    return Html::button('<i class="glyphicon glyphicon-arrow-right"></i>分配',[
                        'title'=>'分配维修',
                        'class'=>'select-maintain btn btn-info btn-sm',
                        'key-openid'=>$key['openid'],
                    ]);
                },
            ]
        ]
    ],
]);

    Modal::end();
    ?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        $('#my-fix-model').hide();
        var machineId,fromOpenid,faultText,hasClick=0;
        $('#my-grid .my-grid-model').click(function(){
            machineId = $(this).attr('data-machine-id');
            fromOpenid = $(this).attr('data-openid');
            $('#my-modal-cancel').modal('show');
            return false;
        });

        $('#go-back').click(function(){
            $('#cancel-btn').show();
            $('#fault-text-form').show();
            $('#my-fix-model').hide();
        });

        $('#cancel-btn').click(function(){
            faultText = $.trim($('#cancel-text').val());
            if(!faultText){
                $('#cancel-status').text('请输入故障描述');
                $('#cancel-text').focus();
                return false;
            }
            $(this).hide();
            $('#fault-text-form').slideUp();
            $('#my-fix-model').show();
        });

        $('#my-fix-model .select-maintain').click(function(){
            if(hasClick == 1) return false;
            hasClick = 1;
            $(this).html('<img src="/images/loading.gif">');
            var $this = $(this);
            var openid = $(this).attr('key-openid');
            var re_count = $(this).closest('tr').children('.repair-count');
            $.post(
                '<?=Url::toRoute(['phonefault'])?>?machine_id='+machineId+'&from_openid='+fromOpenid,
                {'wx_id':<?=$wid?>,'fault_text':faultText,'fault_type':$('#fault-reason').val(),'openid':openid,'fault_remark':$('#fault-remark').val()},
                function(res){
                    if(res.status == 1){
                        re_count.text( parseInt(re_count.text()) + 1 );
                        setTimeout(function(){
                            $this.html('<i class="glyphicon glyphicon-ok"></i>');
                            $('#my-modal-cancel').modal('hide');
                        },1000);
                    }
                    else
                        alert(res.msg);
                    hasClick = 0;
                },'json'
            );
            return false;

        });

//        电话维修，先不分配
        $('#server-sub').click(function(){
            faultText = $.trim($('#cancel-text').val());
            if(!faultText){
                $('#cancel-status').text('请输入故障描述');
                $('#cancel-text').focus();
                return false;
            }

            if(hasClick == 1) return false;
            hasClick = 1;

            $(this).html('<img src="/images/loading.gif">');
            var $this = $(this);
            $.post(
                '<?=Url::toRoute(['/adminrent/phone-no-allot'])?>?machine_id='+machineId+'&from_openid='+fromOpenid,
                {'wx_id':<?=$wid?>,'fault_text':faultText,'fault_type':$('#fault-reason').val(),'fault_remark':$('#fault-remark').val()},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        $this.text('保存维修');
                    }
                    else
                        alert(res.msg);
                    hasClick = 0;
                },'json'
            );
            return false;
        });
        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);