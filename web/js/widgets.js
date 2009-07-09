jQuery(function(){
    $('.widget_close').live('click',function(){
        $(this).parent().parent().parent().fadeOut();
        return false;
    });
    
    $('.widget_top_button img').live('click',function(){
        el = $(this).parent().parent().find('.widget_content');
        el.slideDown();
        $(this).parent().slideUp();
        $(this).parent().parent().find('.widget_bottom_button').slideDown();
        return false;
    });
    
    $('.widget_bottom_button img').live('click',function(){
        el = $(this).parent().parent().find('.widget_content');
        el.slideUp();
        $(this).parent().slideUp();
        $(this).parent().parent().find('.widget_top_button').slideDown();
        return false;
    });
    
    $(".board_col").sortable({"connectWith": ['.board_col'],"handle": '.widget_top_bar'});
    
    
    
    $('.widget_collection_button a').click(function(){
        if($('.widget_collection_container').is(":hidden"))
        {
            $('.widget_collection_container').slideDown();
            $('.widget_collection_button img').attr('src','/images/widgets_expand_up_button.png');
        }
        else
        {
            $('.widget_collection_container').slideUp();
            $('.widget_collection_button img').attr('src','/images/widget_expand_button.png');
        }
        return false;
    });
    
    $('.widget_collection_container a').click(function(){
        title = $(this).find('.widget_prev_title').text();
        $.get("widget_part.html", function(msg){
            $('.board_col:first').append(msg);
            $('.board_col:first li:last .widget_title').text(title);
        });
        $(this).parent().fadeOut();
        return false;
    });
    
    $('.help_close').click(function(){
        $(this).parent().parent().fadeOut();
        return false;
    });
});