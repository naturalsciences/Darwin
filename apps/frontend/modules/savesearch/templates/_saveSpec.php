<!-- <label><?php echo format_number_choice('[1] Save my pinned specimen|(1,+Inf] Save my %1% pinned specimens', array('%1%' =>  count($sf_user->getAllPinned())), count($sf_user->getAllPinned()) );?></label> -->
<label><?php echo __('Save my pinned specimens');?></label>
<select id="save_specs_choice">  <option value=""></option>
<!--  <option value="add"><?php echo __('To an existing list');?></option>-->
  <option value="create"><?php echo __('To a new list');?></option>
</select>
<input type="button" name="save" id="save_specs" value="<?php echo __('Go'); ?>">
<script  type="text/javascript">
$(document).ready(function () {

  $("#save_specs").click(function(){
    if($('#save_specs_choice').val()=="") return;
    column_str = '';
    $('.column_menu ul > li.check').each(function (index)
      {
        if(column_str != '') column_str += '|';
        column_str += $(this).attr('id').substr(3);
      });


    $(this).qtip({
        content: {
            title: { text : '<?php echo __('Save your search')?>', button: 'X' },        
            url: '<?php echo url_for('savesearch/saveSearch?type=pin');?>'+ '/cols/' + column_str + '/action/' + $('#save_specs_choice').val(),
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
</script>