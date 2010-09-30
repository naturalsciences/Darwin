<div>
  <?php if(isset($search) && $search->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage)):?>   
    <?php
      if($orderDir=='asc')
        $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
      else
        $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
    ?>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>      
      <table class="spec_results">
        <thead>
          <tr>
            <th></th>
            <?php $all_columns = $columns->getRaw('specimen') + $columns->getRaw('individual') ;?>

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
          </tr>
        </thead>
        <?php $i = 0 ; ?>
        <?php foreach($search as $specimen):?>
          <tbody>
            <tr>
              <td>
                  <?php echo link_to(image_tag('edit.png', array("title" => __("View"))),'search/view?id='.$specimen->getSpecRef());?>
              </td>
              <?php include_partial('result_content_specimen', array('specimen' => $specimen, 'id' => $i++)); ?>
              <?php include_partial('tagCommonName',array('common_names'=>$common_names->getRawValue(), 'spec'=> $specimen)) ; ?>
              <?php include_partial('result_content_individual', array('specimen' => $specimen)); ?>                                                       
            </tr>            
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
});
</script>
