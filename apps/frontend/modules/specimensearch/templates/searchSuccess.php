<?php slot('title', __('Specimens search result'));  ?>  
     
<div class="encoding">
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>
  
  <div class="page" id="search_div">
    <h1 id="title"><?php echo __('Specimens Search Result');?></h1>
    <form id="specimen_filter" class="search_form" action="<?php echo url_for('specimensearch/searchResult'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
      <table id="intro" style="display:none">
        <?php echo $form->render() ; ?>
      </table>
      <div class="search_results">
        <div class="search_results_content">
          <?php include_partial('searchSuccess',
                                array('specimensearch' => $specimensearch,
                                      'form' => $form, 
                                      'orderBy' => $orderBy,
                                      's_url' => $s_url,
                                      'orderDir' => $orderDir,
                                      'field_to_show' => $field_to_show,
                                      'currentPage' => $currentPage,
                                      'pagerLayout' => $pagerLayout
                                     )
                               ); ?>
        </div>
      </div>
        
        <script  type="text/javascript">
$(document).ready(function () {

  $("#criteria_butt").click(function(){
    $('.search_form').attr('action','<?php echo url_for('specimensearch/search?criteria=1');?>').submit();
  });
});
      </script>
    </form>
      <div class="check_right" id="save_button"> 
        <?php include_partial('savesearch/saveSpec', array('spec_lists'=>$spec_lists));?>
        <?php include_partial('savesearch/saveSearch');?>
      </div>
    <input type="button" id="criteria_butt" value="<?php echo __('Back to criteria'); ?>">
  </div>
</div>
