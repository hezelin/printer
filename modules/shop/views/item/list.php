<?php
use yii\helpers\Url;
$this->title = '商品列表';
?>

    <div class="aui-content" id="top-search">
        <div class="aui-form">
            <div class="aui-input-row">
                <label id="category-list" class="aui-input-addon aui-input-color"><span class="cate-name">全部</span> <span class="aui-iconfont aui-icon-sort aui-size-22"></span> </label>
                <input id="search-input" type="text" class="aui-input"/>
                <span id="search-btn" class="aui-input-addon aui-iconfont aui-icon-search aui-input-color"></span>
            </div>
        </div>

        <?= \yii\helpers\Html::tag('ul',\app\modules\shop\models\Shop::getMenu($id),[
            'class'=>'aui-list-view aui-in',
            'id'=>'fixed-menu',
        ]);
        ?>

    </div>
    <div style="height:40px;">&nbsp;</div>
    <div class="aui-content" id="task-list">
        <?php if( is_array($model) && $model ):?>
            <ul class="aui-list-view" id="item-list">
                <?php foreach($model as $row):?>
                    <li class="aui-list-view-cell aui-img">
                        <a href="<?=Url::toRoute(['detail','id'=>$row['wx_id'],'item_id'=>$row['id']])?>">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <span class="aui-ellipsis-2 my-ellipsis-3 aui-h-60">
                                <?=$row['name']?>
                            </span>
                            <p class="aui-ellipsis-1 aui-text-right">
                                <span class="aui-red-color aui-pull-left">￥<?=$row['price']?></span>
                                <?=$row['category']?>
                            </p>
                        </div>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>

            <?php if(count($model)>=$len):?>
                <div id="item-more" class="item-more-90">
                    查看更多
                </div>
            <?php endif;?>

        <?php else:?>
            <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 没有数据</div>
        <?php endif;?>
    </div>

<?php
\app\assets\ZeptoAsset::register($this);
?>
    <script>

        var q='',
            key='',
            len = <?=$len?>,
            startId = <?=$startId?>,
            action = '/shop/item/list?id=<?=$id?>';
        function getHtml(d){
            var html = [];

            html.push('<li class="aui-list-view-cell aui-img">');
            html.push('<a href="detail?id='+d.wx_id+'&item_id='+d.id+'">');
            html.push('<img class="aui-img-object aui-pull-left" src="'+ d.cover +'">');
            html.push('<div class="aui-img-body"><span class="aui-ellipsis-2 my-ellipsis-3 aui-h-60">');
            html.push(d.name);
            html.push('</span> <p class="aui-ellipsis-1 aui-text-right">');
            html.push('<span class="aui-red-color aui-pull-left">￥'+ d.price +'</span>');
            html.push(d.category);
            html.push('</p></div></a></li>');
            return html.join('');
        }
        function getData(action,key,startId2,q,type)
        {
            $.ajax({
                type:'get',
                url: action,
                data:{'key':key,'q':q,'startId':startId2,'format':'json'},
                dataType:'json',
                success:function(resp){
                    if(resp.status==1){
                        var d = resp.data;
                        startId = resp.startId;
                        var html = [];
                        for(var i in d){
                            html.push( getHtml(d[i]) );
                        }
                        if(type === 'append')
                            $('#item-list').append( html.join('') );
                        else
                            $('#item-list').html( html.join('') );

                        if( resp.len < len )
                            $('#item-more').addClass('item-more-end').text('没有数据了');
                        else
                            $('#item-more').removeClass('item-more-end').text('查看更多');
                    }else{
                        $('#item-more').addClass('item-more-end').text('没有数据了');
                        if(type === 'html')
                            $('#item-list').html('');
                    }
                }
            });
        }
        <?php $this->beginBlock('JS_END') ?>
        $(function(){

            // 点击加载更多
            $('#item-more').click(function(){
                if( $(this).hasClass('item-more-end') ) return false;
                getData(action,key,startId,q,'append');
            });

            // 搜索筛选
            $('#search-btn').click(function(){
                if( $('#search-input').val() ){
                    q = $('#search-input').val();
                    getData(action,key,'',q,'html');
                }
            });

            // 点击类目
            $('#fixed-menu').on('click','li',function(){
                key = $(this).attr('data-key');
                var name = $(this).text();
                getData(action,key,'','','html');

                $('#category-list .cate-name').text(name);
                $('#fixed-menu').hide();
                $('#category-list').removeClass('.has-show');
            });

            // 弹出类目
            $('#category-list').click(function(){
                if($(this).hasClass('.has-show')){
                    $('#fixed-menu').hide();
                    $(this).removeClass('.has-show');
                }else{
                    $('#fixed-menu').show();
                    $(this).addClass('.has-show');
                }
            });
        });
        <?php $this->endBlock(); ?>
    </script>
<?php $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END); ?>