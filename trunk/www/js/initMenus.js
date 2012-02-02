$(document).ready(function(){

    $('.top-menu li.selected').each(function(){
        var id = $(this).attr('id');
        $('ul[id^=ul_submenu] li[id='+id+']').addClass('selected');
    });

    $('ul[id^=ul_submenu] li.selected').each(function(){
        var child_ul = $(this).children('ul:first');

        if( !child_ul.hasClass('open') )
            child_ul.addClass('open');

        $(this).parents('li').each(function(){
            var current_li = $(this);
            if( !current_li.hasClass('open_li') )
                current_li.addClass('open_li');
        });

        $(this).parents('ul').each(function(){
            var current_ul = $(this);
            if( !current_ul.hasClass('open') )
                current_ul.addClass('open');
        });

        var parentid = $(this).parents('li.level-1:first').attr('id');
        $('ul[id^=ul_submenu] li[id='+parentid+']').addClass('selected');
        $('.top-menu li[id='+parentid+']').addClass('selected');
    });

    var nbSelec = $('.collectionsSelected ul li.selected').length;

    if (nbSelec > 1)
    {
        $('.collectionsSelected').addClass('selected');
        $('.collectionsSelected ul li.selected').each(function(){
            $(this).removeClass('selected');
            var href = jQuery(location).attr('href');

            var pattern = "/collection/";
            var findUrl = href.match(pattern);
            if (findUrl)
            {
                var subUrl  = href.substring(findUrl.index + 1, href.length);
                var urlVals = subUrl.split('/') ;
                var curHref = $(this).find('a').attr('href');

                var hasCollection = curHref.match("/" + urlVals[1] +"$");

                if (hasCollection)
                    $(this).addClass('selected');
            }

        });
    }

    var is_selected = true;
    $('ul[id^=ul_top-menu] > li a').not("ul[id^=ul_top-menu] li ul a").each(function()
    {
        var ul  = $(this).parents('li:first').children('ul');
        var div = $(this).parents('li:first').children('div.positionArrow');
        ul.css('visibility', 'hidden');
        div.css('visibility', 'hidden');

        if (!$(this).parent().hasClass('NoSecondLevel'))
        {
            $(this).mouseenter(function()
            {
                var next_ul = ul.css('visibility', 'visible');
                div.css('visibility', 'visible');

                subMenu.show(next_ul, div);
                subMenu.hide(next_ul, div);

            });
        }

        $(this).mouseleave(function()
        {
            ul.css('visibility', 'hidden');
            div.css('visibility', 'hidden');

        });
    });

    $(".top-menu-top li").not('.selected').mouseover(function(){
        $(this).prev('.left').addClass('selectedLeft');
        $(this).next('.right').addClass('selectedRight');
    });

    $(".top-menu-top li").not('.selected').mouseout(function(){
        $(this).prev('.left').removeClass('selectedLeft');
        $(this).next('.right').removeClass('selectedRight');
    });

    var subMenu = {
        show: function(ul, div){

//            ul.each(function()
//            {
//                var elem = $(this);
                ul.mouseenter(function()
                {
                    ul.css('visibility', 'visible');
                    div.css('visibility', 'visible');

                    if( !$(this).parents('li').hasClass('selected') )
                    {
                        $(this).parents('li').addClass('selected');
                        is_selected = false;
                    }
                });
                div.mouseenter(function()
                {
                    if( !$(this).parents('li').hasClass('selected') )
                    {
                        $(this).parents('li').addClass('selected');
                        is_selected = false;
                    }
                    $(this).css('visibility', 'visible');
                    ul.css('visibility', 'visible');
                });

//            });
        },
        hide: function(ul, div){
//            ul.each(function()
//            {
//                var elem = $(this);
                ul.mouseleave(function()
                {
                    ul.css('visibility', 'hidden');
                    div.css('visibility', 'hidden');

                    if( is_selected == false )
                    {
                        is_selected = true;
                        $(this).parents('li').removeClass('selected');
                    }
                });
                div.mouseleave(function()
                {
                    if( is_selected == false )
                    {
                        is_selected = true;
                        $(this).parents('li').removeClass('selected');
                    }
                    $(this).css('visibility', 'hidden');
                    ul.css('visibility', 'hidden');
                });
//            });
        }
    }

});