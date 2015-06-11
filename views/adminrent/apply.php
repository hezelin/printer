<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '租借申请';
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id'=>'apply-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'name',
        'phone',
        [
            'label'=>'昵称',
            'attribute' => 'nickname',
            'value' => 'userInfo.nickname'
        ],
        [
            'attribute'=>'userInfo.headimgurl',
            'format'=>'html',
            'value'=>function($data)
            {
                return Html::a( Html::img( substr($data->userInfo->headimgurl,0,-1) .'46'),
                    $data->userInfo->headimgurl,
                    ['title'=>'查看大图','target'=>'_blank']
                );
            }
        ],
        [
            'attribute'=>'userInfo.sex',
            'value'=>function($data){
                switch($data->userInfo->sex)
                {
                    case 1: return '男';
                    case 2: return '女';
                    default:return '未知';
                }
            }
        ],
        [
            'attribute' => 'add_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:100px'],
            'template' => '{pass} &nbsp; {unpass}',
            'buttons' => [
                'pass' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-ok"></i>',$url,['title'=>'租借通过']);
                },
                'unpass' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,['title'=>'不通过','class'=>'close-model']);
                }
            ]
        ]

    ],
]);



/*
 * 取消租借 模态框
 */
Modal::begin([
    'header' => '租借申请不通过',
    'id' => 'my-modal-cancel',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">不通过</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','不通过并且给用户发送通知',['class'=>'text-primary']);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'资料不齐全无法联系','class'=>'form-control','id'=>'cancel-text']);
echo Html::endForm();

Modal::end();
?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var allotTr;
        var keyId;
        $('#apply-list .close-model').click(function(){
            keyId = $(this).attr('key-id');
            allotTr = $(this).closest('tr');
            $('#my-modal-cancel').modal('show');
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
                '<?=Url::toRoute(['nopass'])?>?rent_id='+keyId,
                {'text':text},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        allotTr.remove();
                    }
                    else
                        alert(res.msg);
                },'json'
            );
        })

        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>