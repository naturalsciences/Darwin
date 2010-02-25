function showWidgetContent(widget)
{
    $(widget).find('.widget_content').slideDown();
    $(widget).find('.widget_bottom_button').slideDown();
    $(widget).find('.widget_top_button').slideUp();
}

function hideWidgetContent(widget)
{
    $(widget).find('.widget_content').slideUp();
    $(widget).find('.widget_bottom_button').slideUp();
    $(widget).find('.widget_top_button').slideDown();
}

jQuery(function(){
    
    $('.widget_top_button img').live('click',function(){
        showWidgetContent($(this).closest('.widget'));
        $.post(chgstatus_url+'/widget/'+$(this).closest('.widget').attr('id')+'/status/open' );
        return false;
    });
    
    $('.widget_bottom_button img').live('click',function(){
        hideWidgetContent($(this).closest('.widget'));
        $.post(chgstatus_url+'/widget/'+$(this).closest('.widget').attr('id')+'/status/close' );
        return false;
    });
    
    $(".board_col").sortable({"connectWith": ['.board_col'],"handle": '.widget_top_bar', "update": updatePositions});

    var notified_col1 = "";
    var notified_col2 = "";
    function updatePositions()
    {
      col_1 = String($(".board_col:first").sortable('toArray'));
      col_2 = String($(".board_col:last").sortable('toArray'));
      if(notified_col1 != col_1 || notified_col2 != col_2)
      {
//         console.log(notified_col1+'-----'+col_1);
//         console.log(notified_col2+'-----'+col_2);
        $.post(chgorder_url, { "col1": col_1, "col2": col_2 } );
        notified_col1 = col_1;
        notified_col2 = col_2;
      }
    }

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
        $.get(this.href, function(msg){
            $('.board_col:first').append(msg);
        });
        $(this).parent().hide();

	if($('.widget_collection_container .widget_preview:visible').length == 0)
	  $('.widget_collection_container .no_more').removeClass('hidden');

	$('.no_more_wigets').addClass('hidden');
        return false;
    });

    $('.widget_refresh').live('click',function(){
        widget = $(this).parent().parent().parent();
        widget.find('.widget_content').load(reload_url+'/widget/'+widget.attr('id'));
        return false;
    });

    $('.widget_close').live('click',function(){
        widget = $(this).parent().parent().parent();
        $.post(chgstatus_url+'/widget/'+widget.attr('id')+'/status/hidden' );
  
	$('.widget_collection_container .no_more').addClass('hidden');

        $('#boardprev_'+widget.attr('id')).fadeIn();

        widget.remove();

	if($('.board_col li').length == 0)
	  $('.no_more_wigets').removeClass('hidden');
        return false;
    });
    
    $('.help_close').click(function(){
        $(this).parent().parent().fadeOut();
        return false;
    });

});