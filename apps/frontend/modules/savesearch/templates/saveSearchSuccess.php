<div class="panel">
  <form id="save_search" class="search_form" action="<?php echo url_for('savesearch/saveSearch'. ( $form->getObject()->isNew() ? '' : '?id='.$form->getObject()->getId()) );?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php echo $form->renderHiddenFields(); ?>
  <table class="form_table">
    <tbody>
    <tr>
      <td><?php echo $form['name']->renderLabel() ; ?></td>
      <td></td>
    </tr>
    <tr>
      <td align="left">
        <?php echo $form['name'] ; ?>
      </td>
      <td>
        <?php echo $form['favorite'] ; ?>      
        <?php echo image_tag('favorite_on.png', array('id'=> 'favorite_on', 'alt' => 'Set a bookmark')) ; ?>
        <?php echo image_tag('favorite_off.png', array('id'=> 'favorite_off', 'alt' => 'Set a bookmark')) ; ?>          
      </td>
    </tr>
    <tr>
      <td><?php echo $form['modification_date_time']->renderLabel() ; ?></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">
        <?php echo $form['modification_date_time']->renderError(); ?>
        <?php echo $form['modification_date_time'] ; ?>
      </td>
    </tr>
    <?php if($is_spec_search):?>
    <tr>
      <td colspan="2"><label><?php echo __("Number of specimens ") ; ?></label></td>
    </tr>
    <tr>
      <td colspan="2">
        <input class="medium_size" disabled="disabled" type="text" value="<?php echo $form->getObject()->getNumberOfIds();?>">
      </td>
    </tr>
    <?php endif;?>
    </tbody>
  </table>
  <br />
  <h2><?php echo("Visibility of fields in results :") ; ?></h2>
    <?php echo $form['visible_fields_in_result']->renderError(); ?>
  <table class="fields">
    <thead>
      <tr>
        <th><?php echo ('Fields') ; ?></th>
        <th><?php echo ('Visible ?') ; ?></th>
      </tr>
    </thead>
    <?php echo $form['visible_fields_in_result'] ; ?>
  </table>
  <div class="aligned_fields"> 
    <?php echo $form['is_only_id']->render(array('class'=>'hidden')) ; ?>
    <input type="submit" name="save" id="save" value="<?php echo __('Save'); ?>" class="search_submit">
  </div>      
  </form>

<script  type="text/javascript">
spec_list_saved = null;
$(document).ready(function () {
  if($('#my_saved_searches_favorite').is(':checked'))
  {
    $('#favorite_on').attr('class','show') ;
    $('#favorite_off').attr('class','hidden') ;      
  }
  else
  {
    $('#favorite_on').attr('class','hidden') ;  
    $('#favorite_off').attr('class','show') ;      
  }

  $('#favorite_on').click(function(){
    $('#favorite_off').attr('class','show') ; 
    $(this).attr('class','hidden') ;
    $('#my_saved_searches_favorite').removeAttr('checked');
  });

  $('#favorite_off').click(function(){
    $('#favorite_on').attr('class','show') ; 
    $(this).attr('class','hidden') ;
    $('#my_saved_searches_favorite').attr('checked','checked');
  });

   $('form#save_search').submit(function () {
      $.ajax({
          type: "POST",
          url: $(this).attr('action'),
          data: $(this).serialize(),
          success: function(html){
            if(/^ok/.test(html))
            {
              id_arr = html.split(',');
              spec_list_saved = id_arr[1];

              $('.qtip-button').click();
            }
            $('form#save_search').parent().before(html).remove();
          }
      });
      return false;
    });
});
</script>

</div>

