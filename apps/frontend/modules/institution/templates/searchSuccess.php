<?php if(isset($items) && $items->count() != 0):?>
  <script type="text/javascript">
    $(document).ready(function () 
    {
      $("#institutions_filters_rec_per_page").change(function ()
      {
        $.ajax({
	        type: "POST",
	        url: "<?php echo url_for($s_url.'&orderby='.$orderBy.'&orderdir='.$orderDir);?>",
	        data: $('#institution_filter').serialize(),
	        success: function(html){
				        $(".search_results_content").html(html);
				       }
	       }
	      );
        $(".search_results_content").html('<?php echo image_tag('loader.gif');?>');
        return false;
      });
      
      $("a.sort").click(function ()
      {
        $.ajax({
                type: "post",
                url: $(this).attr("href"),
                data: $('#institution_filter').serialize(),
                success: function(html){
                                        $(".search_results_content").html(html);
                                       }
               }
              );
        $(".search_results_content").html('<?php echo image_tag('loader.gif');?>');
        return false;
      });
    });
  </script>
  <?php
    if($orderDir=='asc')
      $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
    else
      $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
  ?>
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>
  <div class="results_container">
    <table class="results <?php if($is_choose) echo 'is_choose';?>">
      <thead>
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=family_name'.( ($orderBy=='family_name' && $orderDir=='asc') ? '&orderdir=desc' : '') );?>">
              <?php echo __('Name');?>
              <?php if($orderBy=='family_name') echo $orderSign ?>
            </a>
          </th>
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=additional_names'.( ($orderBy=='additional_names' && $orderDir=='asc') ? '&orderdir=desc' : '') );?>">
              <?php echo __('Abbreviation');?>
              <?php if($orderBy=='additional_names') echo $orderSign ?>
            </a>
          </th>
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=sub_type'.( ($orderBy=='sub_type' && $orderDir=='asc') ? '&orderdir=desc' : '') );?>">
              <?php echo __('Type');?>
              <?php if($orderBy=='sub_type') echo $orderSign ?>
            </a>
          </th>
          <th></th>
      </thead>
      <tbody>
        <?php foreach($items as $item):?>
          <tr class="rid_<?php echo $item->getId();?>">
            <td class="item_name"><?php echo $item->getFamilyName();?></td>
            <td><?php echo $item->getAdditionalNames() ?></td>
            <td><?php echo $item->getSubType() ?></td>
            <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
              <?php if(! $is_choose):?>
                <?php echo link_to(image_tag('edit.png'),'institution/edit?id='.$item->getId());?>
              <?php else:?>
                <div class="result_choose"><?php echo __('Choose');?></div>
              <?php endif;?>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
<?php else:?>
  <?php echo __('No Matching Items');?>
<?php endif;?>