<?php
use app\components\PassthroughWidget;
use yii\helpers\Url;
$this->title = '商品列表';
?>

<?php
echo PassthroughWidget::widget([
    'data'=>$category,
    'startId'=>$startId,
    'action'=>Url::toRoute(['/shop/item/list','id'=>$id]),
]);

?>

    <style>
        <?php $this->beginBlock('CSS') ?>
        .item-list{
            display: block;
            padding: 12px 0;
            width: 90%;
            margin: 0 5%;
            border-bottom: 1px #e3e3e3 solid;
            font-size: 16px;
        }
        .item-list img{
            width: 80px;
            height: 60px;
            float: left;
            margin-right: 10px;
        }
        .item-list span h5{
            height: 50px;
            color: #666;
            font-weight: normal;
            font-size: 16px;
            overflow: hidden;
        }
        .mtm_p{
            text-align: right;
            color: #999;
            font-size: 14px;
        }
        .mtm_p b{
            float: left;
            color: #b10000;
            font-size: 16px;
        }

        .item-more{
            width: 80%; background-color: #efefef;font-size: 14px;
            /*box-shadow: 1px 1px 2px #cccccc; */
            border: 1px solid #EEEEEE;
            text-align: center; height: 36px; line-height: 36px;
            margin: 15px auto; border-radius: 4px; color: #666666;
        }
        .item-more-end{
            background-color: #FFFFFF;color: #cccccc;
            border: 0px;
        }

        .search-btn{
            position: fixed;
            top: 10px;
            right:10px;
            width: 24px;
            height: 24px;
            z-index: 99;
        }
        .search-input{
            position: fixed;
            z-index: 100;
            width: 100%;
            height: 45px;
            border-bottom: 1px solid #ccc;
            background-color: #fff;
        }
        .s-input{
            height: 30px;
            line-height: 30px;
            width: 70%;
            border-radius: 4px 0 0 4px;
            margin: 6px 0 5px 4%;
            border: 1px solid #cccccc;
            box-shadow: 0 0 2px #cccccc;
            font-size: 14px;
            color: #999;
            padding: 0 5px;
            float: left;
        }
        .s-button{
            width: 22%;
            border-radius: 0 4px 4px 0;
            float: left;
            text-align: center;
            background-color: #83cf53;
            color: #FFFFFF;
            font-size: 14px;
            height: 30px;
            padding: 0;
            line-height: 28px;
            margin-top: 6px;
            border: 1px solid #439909;
            border-left:none;
        }
        .s-text{
            text-align: center;
            font-size: 16px;
            display: block;
            line-height: 40px;
            width: 90%;
        }
        #search-show,#search-input{ display: none;}
        <?php $this->endBlock() ?>
    </style>
<?php
$this->registerCss($this->blocks['CSS']);
?>

    <div id="search-wrap">
        <div id="search-input" class="search-input">
            <input class="s-input" type="text" name="q" placeholder="搜索"/>
            <button type="button" class="s-button">搜索</button>
        </div>

        <div id="search-show" class="search-input">
            <span class="s-text"></span>
            <div id="s-close" class="search-btn">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAA7UlEQVRIS92U3Q3DIAyEQTBAN2m6ARVrE5UR0k06AIjGEZGQw89JUR7avAWwP84+I8XFn7w4v/hDgLV2kVKmEMLTe/9BSmiMuWmtX3TWOfcoYw4lyoD7emhBIEXyKaX0nud56gIoQCnlVxVDCE8eYzRcdbXJCARJTkqaLupB0ORdAG3WILSeG7rVvFaWbg+4azgk70PJhwp2GIMI5OZ7LDTJZc1zIGRhSAFv6BYEWBhSUHMLBaJzMrRpyy3InHQVID5HIYcmI8kb7qo2vvnYoVbkSqDXlG44mtByIHcIrQ1fUz7JZ/+hQTsD+X3AF+6sCiiX/5fYAAAAAElFTkSuQmCC" />
            </div>
        </div>

        <div id="s-open" class="search-btn">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABwUlEQVRIS72V3XGCQBSFAX2PJZgKxAqEAZ9NB5oOsIIkFcQOgh2YZ2TECkIHSTog7yo5x9l1EHeBJBhmdtT13vvdf0zjyo+ps+/7vpPn+dQ0TRsyPHxS3KW4W67X66SJbxeA8Xhsw8gzlJ0aAwlA8yiK0iq5MwCNHw6HDRR7UPrCWeD7ShoR8DvcBzg3cCSzLMutgpwAReNQXO73+yBJkkzlneM4vU6nQ/i0DnICIOfM6YjG4zieNcmv53khIZBNUBNXpXMEsKD42DAtu92ur/O8bICRdLvdD6YLx1UV/ggoePIEoccm3ksZOEf5B13kEsDWG+AM67qiDBeFf8N9CueG5f9linL+AQHtXFRFhSi0+v8DQA3+nCLU4BPd11em6OpFlm3KocGA3f6kTTFw72Ly9W0qZkEOWohQ75u0KhxbQW6Cs0WDcJYunrNVgQgI4Y4JEcm8ZlW8wHPuJaOqvS+WXQGSQZH75rW07CaQCURapMcpNoCrcki3rhfQHNWkaQtIgO3LfTTgoKkgdS+cGRRtYcBgK+I33wOh3Dtis/JOCfnV5JYjq4K0AiBQB2kNoIJw+bUKKEL4HfNktw4o1+cb6Tk2KD3lCpYAAAAASUVORK5CYII=" />
        </div>
    </div>
    <div style="height: 56px; width: 100%;">&nbsp;</div>
    <div id="item-list-wrap">
        <?php if($model):?>
            <?php foreach($model as $row):?>
                <a class="item-list" href="<?=Url::toRoute(['detail','id'=>$row['wx_id'],'item_id'=>$row['id']])?>">
                    <img src="<?=$row['cover']?>">
       <span>
           <h5><?=$row['name']?></h5>
           <p class="mtm_p"><b>￥<?=$row['price']?></b><?=$row['category']?></p>
       </span>
                </a>
            <?php endforeach;?>
        <?php endif;?>
    </div>

<?php if(count($model)<10):?>
    <div id="item-more" class="item-more item-more-end">
        没有数据了
    </div>
<?php else:?>
    <div id="item-more" class="item-more">
        查看更多
    </div>
<?php endif;?>