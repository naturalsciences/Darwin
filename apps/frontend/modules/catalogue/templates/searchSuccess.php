<?php if(isset($items) && $items->count() != 0):?>
  <script type="text/javascript">
    $(document).ready(function () 
    {
      $("#searchCatalogue_rec_per_page").change(function ()
      {
        $.ajax({
	        type: "POST",
	        url: "<?php echo url_for('catalogue/search?page='.$currentPage.'&is_choose='.$is_choose);?>",
	        data: $('#search_form').serialize(),
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
                data: $('#search_form').serialize(),
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
  <?php include_partial('global/pager_info', array('form' => $searchForm, 'pagerLayout' => $pagerLayout)); ?>
  <div class="results_container">
    <table class="results <?php if($is_choose) echo 'is_choose';?>">
      <thead>
        <th colspan="3">
          <a class="sort" href="<?php echo url_for($s_url.'&orderby=name_indexed'.( ($orderBy=='name_indexed' && $orderDir=='asc') ? '&orderdir=desc' : '') );?>">
            <?php echo __('Name');?>
            <?php if($orderBy=='name_indexed') echo $orderSign ?>
          </a>
        </th>
      </thead>
      <tbody>
        <?php foreach($items as $item):?>
          <tr class="rid_<?php echo $item->getId();?>">
            <td><?php echo image_tag('info.png',"title=info class=info");?></td>
            <td>
              <span class="item_name"><?php echo $item->getNameWithFormat();?></span>
              <div class="tree">
              </div>
            </td>
            <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if(! $is_choose):?>
                  <?php echo link_to(image_tag('edit.png'),$searchForm->getValue('table').'/edit?id='.$item->getId());?>
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