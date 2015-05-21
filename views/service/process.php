<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '维修进度';
?>
<?php
function getMachine($model){
    return Html::img($model->machine->cover,['width'=>40]) .
            ' , ' . $model->machine->brand .
            ' , ' . $model->machine->type .
            ' , ' . $model->machine->serial_id
        ;
}

function getProcess($model,$process)
{
    $html = '<ul class="list-group">';
    foreach ($process as $k => $p) {
        $row = '';
        $row .= '<i class="h-icon-circle"></i><p class="h-text">';
        $content = json_decode($p['content'], true);
        $row .= \app\models\ConfigBase::getFixStatus($content['status']);
        $row .= '<span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i', $p['add_time']) . '</span></p>';
        $html .= Html::tag('li', $row);
    }

    $html .= Html::tag('li',
            '<i class="h-icon-circle"></i><p class="h-text"><p class="h-text"> 发起维修申请
            <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i',$model->add_time).'</span></p>'
        );
    return $html.'</ul>';
}
?>

<?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-striped detail-view'],
    'attributes' => [
        [
            'format'=>'html',
            'label'=>'故障图片',
            'value'=> Html::a(Html::img($model->cover,['width'=>40]),$model->cover)
        ],

        [
            'attribute'=>'type',
            'value'=> ConfigBase::getFaultStatus($model->type)
        ],
        'desc',
        [
            'label'=>'机器信息',
            'format'=>'html',
            'value'=> getMachine($model),
        ],
        [
            'label'=>'维修进度',
            'format'=>'html',
            'value'=> getProcess($model,$process),
        ]
        /*[
            'attribute'=>'add_time',
            'value' => date("Y-m-d H:i:s",$model->add_time),
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => ConfigBase::getFixStatus($model->status),
        ],*/
    ],
])
    ?>