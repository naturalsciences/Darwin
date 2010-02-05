<?php if(isset($items) && $items->count() != 0):?>
  <div>
    <ul class="pager">
        <li>
          <?php echo $form['rec_per_page']->renderLabel(); echo $form['rec_per_page']->render(); ?>
        </li>
        <?php $pagerLayout->display(); ?>
        <li class="nbrRecTot">
          <span class="nbrRecTotLabel">Total:&nbsp;</span><span class="nbrRecTotValue"><?php echo $pagerLayout->getPager()->getNumResults();?></span>
        </li>
    </ul>
  </div>
  <script type="text/javascript">
    $(document).ready(function () 
    {
      $("#institutions_filters_rec_per_page").change(function ()
      {
        $.ajax({
	        type: "POST",
	        url: "<?php echo url_for('institution/search?page='.$currentPage.'&is_choose='.$is_choose);?>",
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
  <div class="results_container">
    <table class="results <?php if($is_choose) echo 'is_choose';?>">
      <thead>
          <th><?php echo __('Name');?></th>
          <th><?php echo __('Abbreviation');?></th>
          <th><?php echo __('Type');?></th>
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
<?php else:?>
  <?php echo __('No Matching Items');?>
<?php endif;?>