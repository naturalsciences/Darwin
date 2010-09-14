<?php slot('title', __('Search results'));  ?>  
     
<div class="page" id="search_div">
  <h1 id="title"><?php echo __('Search Results');?></h1>
  <?php echo form_tag('search/searchResult', array('class'=>'publicsearch_form','id'=>'specimen_filter'));?>
    <ul id="intro" class="hidden">
      <?php 
        // Render all the form fields as hidden input if possible. if the value is an array or and object render them as usual
        foreach($form as $row)
        { 
        $w = new sfWidgetFormInputHidden();
        $attributes = $form->getWidget($row->getName())->getAttributes();
        if(is_string($row->getValue()) || is_null($row->getValue()))
          echo '<li>'.$w->render( $form->getWidgetSchema()->generateName($row->getName()),$row->getValue(),$attributes).'</li>';
        else
          echo '<li>'.$row.'</li>';
      }?>
    </ul>
    <div class="search_results">
      <div class="search_results_content">
        <?php include_partial('searchSuccess',
                              array('search' => $search,
                                    'form' => $form, 
                                    'orderBy' => $orderBy,
                                    's_url' => $s_url,
                                    'orderDir' => $orderDir,
                                    'field_to_show' => $field_to_show,
                                    'currentPage' => $currentPage,
                                    'pagerLayout' => $pagerLayout,
                                   )
                             ); ?>
      </div>
    </div>
    <script  type="text/javascript">
      $(document).ready(function () {

        $("#criteria_butt").click(function(){
          $('#specimen_filter').attr('action','<?php echo url_for('search/search?criteria=1');?>').submit();
        });

      });
    </script>
    <div class="check_right" id="save_button"> 
      <input type="button" id="criteria_butt" value="<?php echo __('Back to criteria'); ?>">    
    </div> 
  </form>
</div>

