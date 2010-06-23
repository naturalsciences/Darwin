var ref_element_id = null;
var ref_element_name = null;
var ref_level_id = '';
var ref_caller_id = '';
$(document).ready(function () {
 $("a.coll_right").live('click', function(){

    $(this).qtip({
        content: {
            title: { text : 'Choose a User', button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 620, max: 1000},
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
               $('.result_choose_coll_rights').die('click') ;
	          $(this.elements.target).qtip("destroy");
	       }
         }
    });
    return false;
 });
 $("a.set_rights").live('click', function(){
    $(this).qtip({
        content: {
            title: { text : 'List of sub collections', button: 'X' },
            url: $(this).attr('href')
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
            width: { min: 876, max: 1000},
            border: {radius:3},
            title: { background: '#C1CF56', color:'white'}
        },
        api: {
            beforeShow: function()
            {
                // Fade in the modal "blanket" using the defined show speed
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
	          $(this.elements.target).qtip("destroy");
	       }
         }
    });
    return false;
 });
});
function addCollRightValue(user_ref)
{
  $.ajax(
  {
    type: "GET",
    url: $('.add_value a.hidden').attr('href')+ (0+$('.collections_rights tbody tr').length)+'/user_ref/'+user_ref,
    success: function(html)
    {
      $('.collections_rights tbody').append(html);
      $('.collections_rights tbody tr:last').attr("id" , user_ref) ;
    }
  });
  return false;
}

function detachCollRightValue()
{
  parent = $(this).closest('tr');
  $(parent).detach();
}
