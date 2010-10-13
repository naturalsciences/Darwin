var ref_element_id = null;
var ref_element_name = null;
var ref_level_id = '';
var ref_caller_id = '';
$(document).ready(function () {
 $("a.coll_right").click(function(){
    referer = $(this).closest('div').attr('id') ;
    scroll(0,0) ;
    $(this).qtip({
      content: {
        title: { text : 'Choose a User', button: 'X' },
        url: $(this).attr('href')
      },
      show: { when: 'click', ready: true },
      position: {
        target: $(document.body), // Position it via the document body...
        adjust: { y: 210 },
        corner: 'topMiddle' // ...at the topMiddle of the viewport
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
          $('.result_choose').die('click') ;
          $(this.elements.target).qtip("destroy");
        }
      }
    });
    return false;
 });
 
 $("a.set_rights").live('click', function(){
    if ($(this).attr('id') == 'widget') { min_width = 476 } else { min_width = 876 }
    scroll(0,0) ;
    $(this).qtip({
        content: {
            title: { text : 'List of '+$(this).attr('name'), button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            adjust: { y: 210 },
            corner: 'topMiddle', // instead of center, to prevent bad display when the qtip is too big
            adjust:{
        	  	y: 150 // option set in case of the qtip become too big
            },
        },
        hide: false,
        style: {
            width: { min: min_width, max: 1000},
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
function addCollRightValue(user_ref,referer)
{
  $.ajax(
  {
    type: "GET",
    url: $('div#'+referer+' a.hidden').attr('href')+ (0+$('table#'+referer+' tbody tr').length)+'/user_ref/'+user_ref,
    success: function(html)
    {
      $('table#'+referer+' tbody').append(html);
      $('table#'+referer+' tbody tr:last').attr("id" , user_ref) ;
    }
  });
  return false;
}

function detachCollRightValue()
{
  parent = $(this).closest('tr');
  $(parent).detach();
}
