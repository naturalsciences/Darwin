var ref_element_id = null;
var ref_element_name = null;
var ref_level_id = '';
var ref_caller_id = '';
$(document).ready(function () {
  $("a.but_text").live('click', function(event){
    event.preventDefault();
    var last_position = $('body').scrollTop() ;              
    scroll(0,0) ;
    $(this).parent().parent().find('input[type="hidden"]').trigger({ type:"loadref"});
    $(this).qtip({
        content: {
            title: { text : $(this).parent().attr('title'), button: 'X' },
            url: $(this).attr('href')+'?with_js=1'
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            adjust: { y: 210 },
            corner: 'topMiddle' // ...at the topMiddle of the viewport
        },
        hide: false,
        style: {
            width: { min: 876, max: 1000},
            title: { background: '#5BABBD', color: 'white'}
        } ,
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
            if(ref_element_id != null && ref_element_name != null)
            {
              parent_el = $(this.elements.target).parent().prevAll('.ref_name');                           
              parent_el.text(ref_element_name);         
              parent_el.prev().val(ref_element_id);
              $(this.elements.target).parent().prevAll('.ref_clear').show();
              $(this.elements.target).parent().prevAll('.ref_clear').removeClass('hidden');
              $(this.elements.target).find('.off').removeClass('hidden');
              $(this.elements.target).find('.on').addClass('hidden');
              parent_el.prev().trigger('change');
            }
            $('.result_choose_coll_rights').die('click') ;
            $(this.elements.target).qtip("destroy");
            scroll(0,last_position) ;            
        }
      }
    });
    return false;
  });

  $('.ref_clear').live('click',function()
  {
    $(this).prevAll('.ref_name').text('-').show();
    $(this).prevAll('input').val('');
    $(this).next().find('.but_text .on').removeClass('hidden');
    $(this).next().find('.but_text .off').addClass('hidden');
    $(this).hide();
  });
});
