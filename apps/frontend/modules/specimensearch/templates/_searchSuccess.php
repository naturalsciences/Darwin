<div>
      <?php if($is_specimen_search):?>
        <input type="hidden" name="spec_search" value="<?php echo $is_specimen_search;?>" />
      <?php endif;?>
  <?php if(isset($specimensearch) && $specimensearch->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage)):?>   
    <?php
      if($orderDir=='asc')
        $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
      else
        $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
    ?>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>

      <?php echo $source;?>
      <table class="spec_results">
        <thead>
          <tr>
            <th><!-- checkbox for selected when pinned --></th>
            <th><!-- + / - buttons  --></th>
            <th><!-- Pin -->
               <?php echo image_tag('white_pin_off.png', array('class'=>'top_pin_but pin_off','alt' =>  __('Un-Save this result'))) ; ?>
               <?php echo image_tag('white_pin_on.png', array('class'=>'top_pin_but pin_on', 'alt' =>  __('Save this result'))) ; ?>
            </th>
            <?php $all_columns = $columns['specimen']->getRawValue() + $columns['individual']->getRawValue() + $columns['part']->getRawValue() ;?>
            <?php foreach($all_columns as $col_name => $col):?>
              <th class="col_<?php echo $col_name;?>">
                <?php if($col[0] != false):?>
                  <a class="sort" href="<?php echo url_for($s_url.'&orderby='.$col[0].( ($orderBy==$col[0] && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                    $currentPage);?>">
                    <?php echo $col[1];?>
                    <?php if($orderBy == $col[0]) echo $orderSign ?>
                  </a>
                <?php else:?>
                  <?php echo $col[1];?>
                <?php endif;?>
              </th>
            <?php endforeach;?>
            <th><!-- actions --></th>
          </tr>
        </thead>
        <?php foreach($specimensearch as $specimen):?>
          <?php if($source=="specimen") $itemRef = $specimen->getSpecRef();
                elseif($source=="individual") $itemRef = $specimen->getIndividualRef();
                else $itemRef = $specimen->getPartRef(); ?>
          <tbody>
            <tr class="rid_<?php echo $itemRef?>">
              <?php include_partial('result_content_specimen', array('source'=>$source,'specimen' => $specimen, 'codes' => $codes, 'is_specimen_search' => $is_specimen_search)); ?>
              <?php if($source != 'specimen'):?>
                <?php include_partial('result_content_individual', array('specimen' => $specimen, 'codes' => $codes, 'is_specimen_search' => $is_specimen_search)); ?>
              <?php endif;?>
              <?php if($source == 'part'):?>
                <?php include_partial('result_content_part', array('specimen' => $specimen, 'codes' => $codes, 'is_specimen_search' => $is_specimen_search)); ?>
              <?php endif;?>
              <td rowspan="2">
              <?php if($source == 'specimen'):?>
                <?php echo link_to(image_tag('edit.png', array("title" => __("Edit"))),'specimen/edit?id='.$specimen->getSpecRef());?>
                <?php echo link_to(image_tag('duplicate.png', array("title" => __("Duplicate"))),'specimen/new?duplicate_id='.$specimen->getSpecRef(), array('class' => 'duplicate_link'));?>
              <?php elseif($source=="individual"):?>
                <?php echo link_to(image_tag('edit.png', array("title" => __("Edit"))),'individuals/edit?id='.$specimen->getIndividualRef());?>
                <?php echo link_to(image_tag('duplicate.png', array("title" => __("Duplicate"))),'individuals/edit?spec_id='.$specimen->getSpecRef().
      '&duplicate_id='.$specimen->getIndividualRef(),array('class' => 'duplicate_link'));?>
              <?php else:?>
                <?php echo link_to(image_tag('edit.png', array("title" => __("Edit"))),'parts/edit?id='.$specimen->getPartRef());?>
                <?php echo link_to(image_tag('duplicate.png', array("title" => __("Duplicate"))),'parts/edit?indid='.$specimen->getIndividualRef().'&duplicate_id='.$specimen->getPartRef(),array('class' => 'duplicate_link'));?>
              <?php endif;?>
              </td>
            </tr>

            <?php if($source == 'specimen'):?>
              <tr id="tr_individual_<?php echo $specimen->getSpecRef();?>" class="ind_row sub_row">
                <td colspan="14"> 
                  <div id="container_individual_<?php echo $specimen->getSpecRef();?>" class="tree"></div>
                  <script type="text/javascript">
                    $('tr.rid_<?php echo $specimen->getSpecRef(); ?> img.collapsed').click(function() 
                    {
                      $(this).hide();
                      $(this).siblings('.expanded').show();
                      $.get('<?php echo url_for("specimensearch/individualTree?id=".$specimen->getSpecRef()) ;?>',function (html){
                              $('#container_individual_<?php echo $specimen->getSpecRef();?>').html(html).slideDown();
                              });
                    });  
                    $('tr.rid_<?php echo $specimen->getSpecRef(); ?> img.expanded').click(function() 
                    {
                      $(this).hide();
                      $(this).siblings('.collapsed').show();
                      $('#container_individual_<?php echo $specimen->getSpecRef();?>').slideUp();
                    });
                  </script>
                </td>
              </tr>
            <?php elseif($source == 'individual'):?>
              <tr id="tr_part_<?php echo $specimen->getIndividualRef();?>" class="part_row sub_row">
                <td colspan="14"> 
                  <div id="container_part_<?php echo $specimen->getIndividualRef();?>" class="tree"></div>
                  <script type="text/javascript">
                    $('tr.rid_<?php echo $specimen->getIndividualRef(); ?> img.collapsed').click(function() 
                    {
                      $(this).hide();
                      $(this).siblings('.expanded').show();
                      $.get('<?php echo url_for("specimensearch/partTree?id=".$specimen->getIndividualRef()) ;?>',function (html){
                              $('#container_part_<?php echo $specimen->getIndividualRef();?>').html(html).slideDown();
                              });
                    });  
                    $('tr.rid_<?php echo $specimen->getIndividualRef(); ?> img.expanded').click(function() 
                    {
                      $(this).hide();
                      $(this).siblings('.collapsed').show();
                      $('#container_part_<?php echo $specimen->getIndividualRef();?>').slideUp();
                    });
                  </script>
                </td>
              </tr>
            <?php else:?>
              <tr id="tr_part_<?php echo $specimen->getPartRef();?>" class="sub_row">
                <td colspan="14"></td>
              </tr>
            <?php endif;?>
          </tbody>
        <?php endforeach;?>
      </table>

      <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php else:?>
      <?php echo __('No Specimen Matching');?>
    <?php endif;?>
</div>  
<script type="text/javascript">
$(document).ready(function () {
/****COL MANAGEMENT ***/
  $('ul.column_menu > li > ul > li').each(function(){
    hide_or_show($(this));
  });
  initIndividualColspan() ;
  $("ul.column_menu > li > ul > li").click(function(){
    update_list($(this));
    hide_or_show($(this));
  });
/****END COL MANAGEMENT ***/

  /**PIN management **/
  $('.spec_results .pin_but').click(function(){
    if($(this).hasClass('pin_on'))
    {
      $(this).parent().find('.pin_off').removeClass('hidden'); 
      $(this).addClass('hidden') ;
      pin_status = 0;
    }
    else
    {
      $(this).parent().find('.pin_on').removeClass('hidden');
      $(this).addClass('hidden') ;
      pin_status = 1;
    }
    rid = getIdInClasses($(this).closest('tr'));
    $.get('<?php echo url_for('savesearch/pin');?>/id/' + rid + '/status/' + pin_status,function (html){});
  });

  if($('.spec_results tbody .pin_on').not('.hidden').length == $('.spec_results tbody .pin_on').length)
  {
      $('.top_pin_but').parent().find('.pin_on').removeClass('hidden');
      $('.top_pin_but').parent().find('.pin_off').addClass('hidden') ;
  }
  else
  {
      $('.top_pin_but').parent().find('.pin_off').removeClass('hidden');
      $('.top_pin_but').parent().find('.pin_on').addClass('hidden') ;
  }
  
  $('.spec_results .top_pin_but').click(function(){
    /** Multiple pin behavior ***/
    if($(this).hasClass('pin_on'))
    {
      $(this).parent().find('.pin_off').removeClass('hidden'); 
      $(this).addClass('hidden') ;
      pin_status = 0;
    }
    else
    {
      $(this).parent().find('.pin_on').removeClass('hidden');
      $(this).addClass('hidden') ;
      pin_status = 1;
    }
    pins = '';
    $('.spec_results tbody tr').not('.ind_row').each(function(){
      rid = getIdInClasses($(this));
      if(pins == '')
        pins = rid;
      else
        pins += ','+rid;
    });

    if(pin_status == 0)
    {
        $('.spec_results tbody tr .pin_off').removeClass('hidden');
        $('.spec_results tbody tr .pin_on').addClass('hidden') ;
    }
    else
    {
        $('.spec_results tbody tr .pin_off').addClass('hidden');
        $('.spec_results tbody tr .pin_on').removeClass('hidden') ;
    }
    $.get('<?php echo url_for('savesearch/pin');?>/mid/' + pins + '/status/' + pin_status,function (html){});
  }); 

});
</script>
