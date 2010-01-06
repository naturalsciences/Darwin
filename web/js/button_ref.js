var ref_element_id = null;
var ref_element_name = null;
$(document).ready(function () {

  $("a.but_text").live('click', function(){
    $(this).qtip({
        content: {
            title: { text : $(this).parent().attr('title'), button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 600, max: 1000}
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
	    {		console.log('--'+ref_element_name);

		if(ref_element_id != null && ref_element_name != null)
		{
		  parent_el = $(this.elements.target).parent().prevAll('.ref_name');

		  parent_el.text(ref_element_name);
		  parent_el.prev().val(ref_element_id);
		  $(this.elements.target).parent().prevAll('.ref_clear').show();
		  $(this.elements.target).text('Change !');
		}
		$(this.elements.target).qtip("destroy");
	    }
        }
    });
    return false;
  });

  $('.ref_clear').live('click',function()
  {
    $(this).prevAll('.ref_name').text('');
    $(this).prevAll('input').val('');
    $(this).next().find('.but_text').text('Choose !');
    $(this).hide();
  });
});