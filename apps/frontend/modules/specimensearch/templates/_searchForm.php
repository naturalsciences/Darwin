<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="page" id="search_div">
  <div class="check_right hidden" id="back_button"> 
    <input type="submit" name="back" id="back_to_search" value="<?php echo __('Back'); ?>" class="search_submit">
  </div>   
  <h1 id="title"><?php echo __('Specimens Search');?></h1>
  <form id="specimen_filter" class="search_form" action="" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="panel encod_screen" id="intro">
      <input id="fields_to_show" type="hidden" name="fields_to_show" value="<?php echo $fields ;?>">
      <?php include_partial('widgets/screen', array(
        'widgets' => $widgets,
        'category' => 'specimensearchwidget',
        'columns' => 2,
        'options' => array('form' => $form),
      )); ?>  
      <p class="clear"> </p>
		  <p class="form_buttons">
        <div class="check_right"> 
          <input type="submit" name="submit" id="submit" value="<?php echo __('Search'); ?>" class="search_submit">
        </div>
    </div>     
    <div class="search_results">
      <div class="search_results_content">
      </div>
    </div>  
   <div class="check_right" id="save_button"> 
    <input type="button" name="save" id="save_search" value="<?php echo __('Save this search'); ?>" class="save_search">
  </div>   
  </form>
</div>
<script>
$(document).ready(function () {
 $("#submit").click(function(){ 
    $('div#intro').addClass('hidden') ;
    $('h1#title').html('<?php echo __("Search result");?>') ;
    $('div#back_button').removeClass('hidden') ;
    $('form').attr('action','<?php echo url_for('specimensearch/search');?>').submit();      
 });
 $('#back_to_search').click(function(){
    $('div#back_button').addClass('hidden') ;
    $('div#intro').removeClass('hidden') ;
    $('div#save_button').removeClass('hidden') ;
    $('h1#title').html('<?php echo __("Specimens Search");?>') ;
    $('div.search_results_content').html('') 
 }); 
 $('#save_search').click(function() {
    $('div#intro').addClass('hidden') ;
    $('div#save_button').addClass('hidden') ;    
    $('h1#title').html('<?php echo ("Save your search criterias") ; ?>');
    $('div#back_button').removeClass('hidden') ;
    $('form').attr('action','<?php echo url_for('specimensearch/saveSearch');?>').submit();    
 });
});
</script>



