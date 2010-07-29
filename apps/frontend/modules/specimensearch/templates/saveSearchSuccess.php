<div class="panel">
  <form id="save_search" class="search_form" action="<?php echo url_for('specimensearch/processSave');?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <h1><?php echo("Title :") ; ?></h1>
  <?php echo $form->renderHiddenFields(); ?>
  <table>
    <tbody>
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
      <td colspan="2"><?php echo $form['modification_date_time'] ; ?></td>
    </tr>    
    </tbody>
  </table>
  <br />
  <h1><?php echo("Visibility of fields in results :") ; ?></h1>
  <table class="fields">
    <thead>
      <tr>
        <th><?php echo ('Fields') ; ?></th>
        <th><?php echo ('Visible ?') ; ?></th>
      </tr>
    </thead>
    <tbody>
    <?php echo $form['visible_fields_in_result'] ; ?>
    </tbody>
  </table>
  <div class="aligned_fields"> 
    <input type="submit" name="save" id="save" value="<?php echo __('Save'); ?>" class="search_submit">
  </div>      
  </form>
</div> 
<script>
$(document).ready(function () {
  if($('#my_saved_searches_favorite').attr('checked') == 'checked')
  {
    $('#favorite_on').attr('class','show') ;
    $('#favorite_off').attr('class','hidden') ;      
  }
  else
  {
    $('#favorite_on').attr('class','hidden') ;  
    $('#favorite_off').attr('class','show') ;      
  }
});
$('#favorite_on').click(function(){
  $('#favorite_off').attr('class','show') ; 
  $(this).attr('class','hidden') ;
  $('#my_saved_searches_favorite').attr('checked','');
});
$('#favorite_off').click(function(){
  $('#favorite_on').attr('class','show') ; 
  $(this).attr('class','hidden') ;
  $('#my_saved_searches_favorite').attr('checked','checked');
});
</script>
