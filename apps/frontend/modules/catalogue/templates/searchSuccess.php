<?php if(isset($items) && $items->count() != 0):?>
<table class="results <?php if($is_choose) echo 'is_choose';?>">
  <thead>
    <th colspan="3">Search Result</td>
  </thead>
  <tbody>
  <?php foreach($items as $item):?>
    <tr class="rid_<?php echo $item->getId();?>">
      <td><?php echo image_tag('info.png',"title=info class=info");?></td>
      <td>
	<span class="item_name"><?php echo $item->getName();?></span>

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