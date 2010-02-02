<?php if(isset($items) && $items->count() != 0):?>
<div>
  <ul class="pager">
      <li>
	  <?php echo $searchForm['rec_per_page']->renderLabel(); echo $searchForm['rec_per_page']->render(); ?>
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
  });
</script>

<table class="results <?php if($is_choose) echo 'is_choose';?>">
  <thead>
    <th colspan="3">Search Result</td>
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
<?php else:?>
  <?php echo __('No Matching Items');?>
<?php endif;?>