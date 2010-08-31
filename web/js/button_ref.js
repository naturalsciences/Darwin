var ref_element_id = null;
var ref_element_name = null;
var ref_level_id = '';
var ref_caller_id = '';
$(document).ready(function () {
  $("a.but_text").live('click', function(){
        scroll(0,0) ;

    $(this).parent().parent().find('input[type="hidden"]').trigger({ type:"loadref"});
    $(this).qtip({
        content: {
            title: { text : $(this).parent().attr('title'), button: 'X' },
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
            width: { min: 876, max: 1000},
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
            if(ref_element_id != null && ref_element_name != null)
            {
              parent_el = $(this.elements.target).parent().prevall('.ref_name');
              parent_el.text(ref_element_name);
              parent_el.prev().val(ref_element_id);
              $(this.elements.target).parent().prevall('.ref_clear').show();
              $(this.elements.target).parent().prevall('.ref_clear').removeclass('hidden');
              $(this.elements.target).text('change !');
              parent_el.prev().trigger('change');
            }
                  $('.result_choose_coll_rights').die('click') ;
            $(this.elements.target).qtip("destroy");
              }
        }
    });
    return false;
  });

  $('.ref_clear').live('click',function()
  {
    $(this).prevAll('.ref_name').text('-').show();
    $(this).prevAll('input').val('');
    $(this).next().find('.but_text').text('Choose !');
    $(this).hide();
  });
});
