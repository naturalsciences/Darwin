<?php if(isset($items) && $items->count() != 0):?>
<ul>
  <?php foreach($items as $item):?>
    <li class="rid_<?php echo $item->getId();?>"><?php echo $item->getName();?></li><?php //echo link_to('(e)','taxonomy/edit?id='.$taxa->getId());?>
  <?php endforeach;?>
</ul>
<?php else:?>
  <?php echo __('No Matching Items');?>
<?php endif;?>