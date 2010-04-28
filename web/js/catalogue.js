function addError(html, element)
{
  $(element).closest('.widget_content').find('.error_list li').text(html);
  $(element).closest('.widget_content').find('.error_list').show();
}

function removeError(element)
{
  $(element).closest('.widget_content').find('.error_list').hide();
  $(element).closest('.widget_content').find('.error_list li').text(' ');
}

$(document).ready(function () {
 $("a.link_catalogue").live('click', function(){
    $(this).qtip({
        content: {
            title: { text : $(this).attr('title'), button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 876, max: 1000},
            border: {radius:3},
            title: { background: '#C1CF56', color:'white'}
        },
        api: {
            beforeShow: function()
            {
                addBlackScreen()
                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
            },
            beforeHide: function()
            {
                // Fade out the modal "blanket" using the defined hide speed
                $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
            },
	    onHide: function()
	    {
		widget_parent = $(this.elements.target).closest('li.widget');
		widget_parent.find('.widget_content').load(reload_url+'/widget/'+widget_parent.attr("id"));
		$(this.elements.target).qtip("destroy");
		hideForRefresh(widget_parent.find('.widget_content'));
	    }
        }
    });
    return false;
 });

 $("a.widget_row_delete").live('click', function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError($(this));
       $.ajax({
               url: $(this).attr('href'),
               success: function(html) {
		      if(html == "ok" )
		      {
			//We are in a widget
			if(currentElement.parent().hasClass('widget_row_delete'))
			{
			  widget_parent = currentElement.closest('li.widget');
			  widget_parent.find('.widget_content').load(reload_url+'/widget/'+widget_parent.attr("id"));
			  hideForRefresh(widget_parent.find('.widget_content'));
			}
			else //We are into a qtip element
			{
			  $('.qtip-button').click();
			}
		      }
		      else
		      {
			addError(html, currentElement); //@TODO:change this!
		      }
		},
               error: function(xhr){
		  addError('Error!  Status = ' + xhr.status);
               }
             }
            );
    }
    return false;
 });

});
