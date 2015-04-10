/**
 * Created by Administrator on 2015/4/10.
 * 左下角导航菜单
 */
$(function(){
    $(".plug-menu").click(function(){
        var li = $(this).parents('ul').find('li');
        if(li.attr("class") == "themeStyle on"){
            li.removeClass("themeStyle on");
            li.addClass("themeStyle out");
        }else{
            li.removeClass("themeStyle out");
            li.addClass("themeStyle on");
        }
    });
});