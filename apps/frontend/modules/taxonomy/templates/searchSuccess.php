<?php if(isset($taxons) && $taxons->count() != 0):?>
<ul>
  <?php foreach($taxons as $taxa):?>
    <li class="rid_<?php echo $taxa->getId();?>"><?php echo $taxa->getName();?></li><?php //echo link_to('(e)','taxonomy/edit?id='.$taxa->getId());?>
  <?php endforeach;?>
</ul>
<?php else:?>
  <?php echo __('No Taxa Matching');?>
<?php endif;?>