<?php
use yii\helpers\Url;
use app\components\CarouselWidget;
use app\components\FixedmenuWidget;

$this->title = $setting['store_name'];

$this->registerCssFile('/css/home/'.$setting['style']);
//$this->registerJsFile('/js/iscroll5.js');

?>
<style>
    body{ background-color: #FFFFFF;}
</style>
<?= FixedmenuWidget::widget(['menu'=>[
    ['url'=>Url::toRoute(['i/help']),'name'=>'<img src="/images/plugmenu3.png"><label>微信帮助</label>'],
    ['url'=>Url::toRoute(['i/phone']),'name'=>'<img src="/images/plugmenu1.png"><label>客服热线</label>'],
]])?>

<?= CarouselWidget::widget(['data'=>$setting['carousel']]) ?>
<!--首页菜单-->
<ul id="home-menu">
    <li><a href="<?= Url::toRoute(['i/machine','id'=>$setting['wx_id']]) ?>" ><b class="color-1"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAA5UlEQVRYR+2WjQnCMBCFkwnECdRJdBSdTEfRSdpu4Ab6HjQQRHvHJUcs5KC0kN7d1/ttDI0lNvYfRIAXpAQyQpb0O8D/R6Ak/xpdMQIaIyXvRBT5CQauuPYlhgy6I3QuBODDzmCghspIgNTnD1g8z1ZvuB9reJBs5AAHzAxGI4CJ6Rgk5RrnOcAWAM+WAHcWxfxVLEoWp7vkEXB39s1BB1hPBKS9/plf7X+EOgKeAOz9jdQCTgBTWkYcvYv7wAFg4ugX13HKpRVA0usAPQLriYA0J36dV+uCZgBWx1o9sQa0hqzvvQEYbqIsjkXnxAAAAABJRU5ErkJggg==" /></b><span>我的机器</span></a></li>
    <li><a href="<?= Url::toRoute(['share/scheme','id'=>$setting['wx_id']]) ?>" ><b class="color-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABbElEQVRYR+1W7U3DMBCtR2ADNqFswAbtBlUnADboCHSC0g3aSWAENgjvVWcUnDvXd6mU/oilyEnsl3v35Ze0mHikie0vZgJmBLque0F6NriWkqYT5veUEmdzeHEqAXzkAxZWhhWSeNPWIrgBAfHgIAa2mD/lfo35Ve6fy0hEcRoBhvgJ1xZGdn1PYYSek8QZazk1ly1YC+E0Ap0YfYCRn4LAI56/+A5r/7AgEMK5CIinF0MWgfK9YEzitRSoxZY9dRLIqTsCx+76G9eKkMA9QN8Z4SGAvfSc3cTvcAyK12rDzLhPduy9GtHaQbQU5uyIMePM71gHmFsLPCloYT0TuN8IKKrWklJtzwkvTRWNqGGUSFsbaqo28iBag7Gpoi41pOuRNqyp6F2KUUhWbynHrFoev4Oi6YVyoGq9HxIXrkkNpeyrqlYUL8Vs34KLqGHtp7Smom1tmJscHpVqWFW1KM59FEdPIQs3E5g8Ar9SBjYwgEz0ogAAAABJRU5ErkJggg==" /></b><span>赚取积分</span></a></li>
    <li><a id="scan-btn" href="javascript:void(0);" ><b class="color-3"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAA0ElEQVRYR2NkGGDAOMD2M4w6ACME/v//LwCMln4gTsAWPYxAgC/agPr/45BfABQvBGr/gCyPzQEghfG4LKHAASAjFwL1o3gMmwNgPvgI1HAB3SFAAxwIhMABLPIGQDF+kDi6B/A54CAhy4jNQcBYATnKftQBoyFAVAgQm7CopW60KB4NgdEQGHwhgFSfj1ZGoyEw4CEAajxia5I5EtEkQ2+YgppkoMYuUU2yAW+Uglw6AVfLmIJW8UKgmQUEm+XUqueJNWfwlYTEupxa6gY8BABEipUhcIXH9wAAAABJRU5ErkJggg==" /></b><span>扫描报修</span></a></li>
    <li><a href="<?= Url::toRoute(['mall/index','id'=>$setting['wx_id']]) ?>" ><b class="color-4"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACNUlEQVRYR8VXi1ECMRD1KpAOhAqUCjw70ArEDrACzwosQaxAOxAqECvwrECo4HyPSZjcXnK75GYgM5mI7Nt9+0l2Kc5OvIpc+03TXAH74vCPRVGsc3TtCDhl54GCraYQmBLynw5zA/nlEAIEXwsFEyitU0qPQeAZBKpjEWA+R87YAucF9gabUeDp0+Rzzn9Rnjgu5n8n51arJly0RtD1IR3qFCGE5xDyhvZRECHX0t2qCWB9inneeaeoJEaAntXYLMp9FHIJCNwKxsuQffQaAlRB6MkJRmvBWoSQozNMKVensFMEwijUYD2RMbcQgMwMuFeHfYMefm6t5EMkovAA8CJEagTwPZ34wea5xR6Hufe6+ggQ+OcEO1EwEFDTSN29TzGM0Ot7R6ITBRlO/9nqvYXA2IWRsmuEcJoyKtJjJq42IxEFFlGtkGDq/IPzC9J0IrksBG6Bfrd4HpFRm5SFwBKKZaOy8IleOwnUijD0fgVwZbHMNPV10lCHRoD5Tr5iRjK9Yn3vQAmkHzhM4cwhZCWQozvEJItRS0FuAUrC2QR4p2fYLMYha46ijA6t6jUcYtWC1VLAkYvtlOcCm6NWOHp1bLg+wImKkaPX7CHJkV3rhr6dekPqbRBPN3Gt2VIyPvgWwBstak0k9IcXofux8iWUfcO+n4SjKQaO4b4UX06zilBMRZxqyr580qgjvsSf/pdWdKb0BNVbAIVjCHNzHugtQK/UFSIjtdEIqwQsV2mIzMkJ/AMnoxYwF+eHuQAAAABJRU5ErkJggg==" /></b><span>微商城</span></a></li>
    <li><a href="<?= Url::toRoute(['share/active','id'=>$setting['wx_id']]) ?>" ><b class="color-5"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAByUlEQVRYR+2W4VECMRCFvQrUCsQK1ArECtQKhAqECrQDpQKhA6hArUDpACsQKzjfd7Mbz3DxDmZw+HE7kwnZZN++vN0A2d4WLM/zA8FeanQMfp5l2bQqVZbKbyB32j+1M1OBjOr4Km6gM8RBomxjxffj+EoCAoH5WwXIu3zXAlpUEVHcWP4b2/vSzHmInJivr1jOBEsRcCBAHjUg5MCAXghoWQZS8p7WT+abaB74mRKxmXxXTQiQBNaBsUAoBapgIwEhdWHR3kR7kAmm/XstKMur9rpNCCx06EhjqAAU8EQkfbDlsZdCCV7kO9eYa3Qr1HG8X8TBSZWApLcaBJ6VAZXMwYqbas2Nno0UpYFMMGtKJw0W6gZLEaBxSLRvM6UogEu1Xsp3WLr9irxRX6zUP6mAJeJmvF1IYGMNmosbfJpvqNlvF25vT5iG9IarLM2fBIwEjUdif0a4UQaFGLwE5g+p0bHny2uhV/x7YKbPvbgv7AI/PaDg3J3/MYtQUf7QAztDwJnVqRATXjcuqcC6QKGWJmlT4i2B3W3CpjVdtwlT51e+iv1gXTNuSiDGbQm0CrQKtAq0CuyuAnV/LDbdb/xbsGmCuriYwDcBxX4wXQnoxAAAAABJRU5ErkJggg==" /></b><span>最新活动</span></a></li>
    <li><a href="<?= Url::toRoute(['share/game','id'=>$setting['wx_id']]) ?>" ><b class="color-6"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACNklEQVRYR8VXgU3DMBBsJoANaCcAJqCdgHYCugEwAXQCYAJgAmACwgSwAWGDdoJwF9mWndj+N0GqpSqt8j7fn+/fbjXZ86j2vP5ETaBt20OQfcWnVZB+q6rqXhGnJ0AwkCDopQYYMTOQaKRYlQLMHmBbowJBDyRgqoU5KylOJIBF5wBZAuzKqLDG81ECNu8XmFfnYjUEmPGRLylIfeH3sYJEAwKzPxPAQsz6zgDUAFsYFajKu4IAQ65zhkwqYPb7GwB0vx0rgLESaEg+zxUktkY9PgcjRyDmeCcpCEyBxq3QGPLBeqjPIEoA4CcI/ExktwHYrVGBzxuFCgyJlmWKQI0JZwlgJ6nZJqpAk0rDecgPHBAA6BIBLwLaM1RYGxX41Jal85DFjxFolBmdggSzpyFzivm5DMoyIACgkj11TcY0K21ZOg+RmSOQKLvUTrgtsAGY/4TvF5IR8D4oS5+AFmAHkCnPBn+xwnPCJdARGCNhj0TJFnYesgS0JvrBpKlCZnVIhexHlZF6pUQgCWhPtg9kP/dMR+KaBpTjuCMB7b65Viq06hJRNiTA005qp8FhUtB4cmQ6P1kT5tpvUHbKVq1RoWvLfh9IVUJwoQCB5h/23vnJJxA7goOyK/CLpIA7R/pnQb8b+v2eXunfkKSFYu+DNt4nwEUoMW85/HNBb3SjoNdnyw4vgzYeO45tWfplx/rXnnY5AoMLaupGtEb23A6bPUmRxJix9RW1QOL/gjEraubuncAvZkQHGjlZs3AAAAAASUVORK5CYII="/> </b><span>游戏中心</span></a></li>
</ul>

<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$setting['wx_id'],
    'apiList'=>['scanQRCode'],
    'jsReady'=>'
    document.querySelector("#scan-btn").onclick = function () {
        wx.scanQRCode({
            needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
            var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
        }
        });
    };'
])
?>