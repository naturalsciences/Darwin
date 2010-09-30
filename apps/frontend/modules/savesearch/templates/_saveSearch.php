<input type="button" name="save" id="save_search" value="<?php echo __('Save this search'); ?>" class="save_search">

<script  type="text/javascript">
$(document).ready(function () {

  $("#save_search").click(function(){
    source = '<?php if(isset($source)) echo $source;?>';
    column_str = ' ';
    if($('.column_menu ul > li.check').length)
    {
      $('.column_menu ul > li.check').each(function (index)
      {
        if(column_str != '') column_str += '|';
        column_str += $(this).attr('id').substr(3);
      });
    }
    else
    {
      column_str = $('#specimen_search_filters_col_fields').val();
    }

    scroll(0,0) ;

    $('form.specimensearch_form select.double_list_select-selected option').attr('selected', 'selected');
    if(source == '')
      source = $('#specimen_search_filters_what_searched').val();
    $(this).qtip({
        content: {
            title: { text : '<?php echo __('Save your search')?>', button: 'X' },        
            url: '<?php echo url_for("savesearch/saveSearch");?>/source/' + source + '/cols/' + column_str,
            data: $('.specimensearch_form').serialize(),
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
</script>
