function update_list(li)
{
  val = li.attr('class') ;
  if (val == 'check')
  {
    li.removeClass('check') ;
    li.addClass('uncheck') ; 
  }
  else
  {
    li.removeClass('uncheck') ;
    li.addClass('check') ; 
  }
}
function hide_or_show(li)
{
  field = li.attr('id') ;
  column = field.substr(3) ;
  val = li.attr('class') ;
  colspan = $('table.spec_results tbody tr:nth-child(2) td').attr('colspan') ;
  if(val == 'uncheck')
  {
    $("li #"+field).find('span:first').hide();
    $("li #"+field).find('span:nth-child(2)').show();
    $('table.spec_results thead tr th.col_'+column).hide();
    $('table.spec_results tbody tr td.col_'+column).hide();
    //this line below is neccessary to avoid table border to be cut
    $('table.spec_results tbody tr:nth-child(2) td').attr('colspan',colspan-1) ;
  }
  else
  {
    $("li #"+field).find('span:first').show();
    $("li #"+field).find('span:nth-child(2)').hide();
    $('table.spec_results thead tr th.col_'+column).show();
    $('table.spec_results tbody tr td.col_'+column).show();
    //this line below is neccessary to avoid table border to be cut    
    $('table.spec_results tbody tr:nth-child(2) td').attr('colspan',colspan+1) ;    
  }
}

$(document).ready(function () {
  $("#save_search").click(function(){
    $(this).qtip({
        content: {
            title: { text : 'Save your search', button: 'X' },        
            url: 'saveSearch',
            data: $('.search_form').serialize(),            
            method: 'post'
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'topMiddle', // instead of center, to prevent bad display when the qtip is too big
            adjust:{
        	  	y: 150 // option set in case of the qtip become too big
            },
        },
        hide: false,
        style: {
            width: { min: 620, max: 800},
            border: {radius:3},
            title: { background: '#5BABBD', color:'white'}
        },
        api: {
            beforeShow: function()
            {
                // Fade in the modal "blanket" using the defined show speed
			         ref_element_id = null;
			         ref_element_name = null;
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
	          $(this).attr('value','Search Saved') ;
	          $(this.elements.target).qtip("destroy");
	       }
         }
    });
    return false;
 });
}); 
