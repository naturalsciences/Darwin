(function($) {

   $.catalogue_people = function(element, options) {
      options = $.extend({}, $.fn.catalogue_people.defaultOptions, options);
      $(element).find('>tbody').sortable({
        placeholder: 'ui-state-highlight',
        handle: options['handle'],
        axis: 'y',
        change: function(e, ui) {
              $.fn.catalogue_people.forceCollectorsHelper(e,ui);
            },
        deactivate: function(event, ui) {
              $.fn.catalogue_people.reorder(element);
        }
      });

      $(options['add_button']).click(function(event){
        event.preventDefault();
        var last_position = $('body').scrollTop() ;      
        scroll(0,0) ;
        $(this).qtip({
          content: {
            title: { text: options['q_tip_text'], button: 'X' },
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
            title: { background: '#5BABBD', color:'white'}
          },
          api: {
            beforeShow: function()
            {
                // Fade in the modal "blanket" using the defined show speed
              ref_element_id = null;
              ref_element_name = null;
              fct_update = options['update_row_fct'];
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
              scroll(0,last_position) ;
              $(this.elements.target).qtip("destroy");
            }
          }
        });
        return false;
      });      
      return this;
   };
  
  $.fn.catalogue_people = function(options) {
    return this.each(function() {
       (new $.catalogue_people($(this), options));
    });
  };

  $.fn.catalogue_people.forceCollectorsHelper = function (e,ui){
    $(".ui-state-highlight").html("<td colspan='3' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");
  };
  
  $.fn.catalogue_people.reorder = function(el){
      $(el).find('tr:visible').each(function (index, item){
        $(item).find('input[id$=\"_order_by\"]').val(index+1);
      });
    };
    
  $.fn.catalogue_people.defaultOptions = {
    table_col_num: 3,
    handle: '.spec_ident_collectors_handle',
    add_button: 'a#add_button',
    q_tip_text: 'Choose a Member',
    update_row_fct: undefined
  }

})(jQuery);




