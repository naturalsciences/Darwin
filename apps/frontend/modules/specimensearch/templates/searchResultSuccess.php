<?php slot('title', __('Specimens search result'));  ?>  
     
<div class="encoding">
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>
  
  <div class="page" id="search_div">
    <h1 id="title"><?php echo __('Specimens Search Result');?></h1>
    <form id="specimen_filter" class="search_form" action="specimensearch/searchResult" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
      <ul id="intro" style="display:none">
        <?php echo $form->render() ; ?>
      </ul>
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
     <div class="check_right" id="save_button"> 
      <input type="button" name="save" id="save_search" value="<?php echo __('Save this search'); ?>" class="save_search">
    </div>
    </form>
  </div>
</div>
