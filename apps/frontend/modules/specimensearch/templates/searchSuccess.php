<?php slot('title', __('Specimens search result'));  ?>  
     
<div class="encoding">
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>
  
  <div class="page" id="search_div">
    <h1 id="title"><?php echo __('Specimens Search Result');?></h1>
    <?php echo form_tag('specimensearch/search'.( isset($is_choose) ? '?is_choose='.$is_choose : '') , array('class'=>'specimensearch_form','id'=>'specimen_filter'));?>
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
                                array('specimensearch' => $specimensearch,
                                      'codes' => $codes,
                                      'form' => $form, 
                                      'orderBy' => $orderBy,
                                      's_url' => $s_url,
                                      'orderDir' => $orderDir,
                                      'field_to_show' => $field_to_show,
                                      'currentPage' => $currentPage,
                                      'pagerLayout' => $pagerLayout,
                                      'is_specimen_search' => $is_specimen_search
                                     )
                               ); ?>
        </div>
      </div>
      <?php if(isset($is_pinned_only_search)):?>
        <input type="hidden" name="pinned" value="true" />
      <?php endif;?>
        <script  type="text/javascript">
$(document).ready(function () {

  $("#criteria_butt").click(function(){
    $('#specimen_filter').attr('action','<?php echo url_for('specimensearch/search?criteria=1');?>').submit();
  });

  <?php if($is_specimen_search):?>
    $('#del_from_spec').click(function(){
      pins = '';
      pins_array = new Array();
      $('.spec_results tbody tr input.spec_selected:checked').each(function(){
        rid = getIdInClasses($(this).closest('tr'));
        pins_array.push(rid);
      });
      if(pins_array.length == 0)
      {
        alert("<?php echo __('You must select at least one specimen.');?>");
      }
      else
      {
        if(confirm('<?php echo __('Are you sure?');?>'))
        {
          $.get('<?php echo url_for('savesearch/removePin?search='.$is_specimen_search);?>/ids/' + pins_array.join(',') ,function (html){
            //$('.rid_'+rid).closest('tbody').remove();
            for(var i = 0; i < pins_array.length; ++i)
            {
              $('.rid_' + pins_array[i]).closest('tbody').remove();
            }
          });
        }
      }
    });
  <?php endif;?>

});
      </script>
    </form>
      <div class="check_right" id="save_button"> 

        <?php include_partial('savesearch/saveSpec', array('spec_lists'=>$spec_lists));?>

        <?php if(! $is_specimen_search):?>
          <?php include_partial('savesearch/saveSearch');?>
        <?php endif;?>
      </div>
      <?php if(!isset($is_pinned_only_search) && ! $is_specimen_search):?>
        <input type="button" id="criteria_butt" value="<?php echo __('Back to criteria'); ?>">
      <?php else:?>
        <input type="button" id="del_from_spec" value="<?php echo __('Remove selected'); ?>">
      <?php endif;?>     
  </div>
</div>
