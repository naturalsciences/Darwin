<?php if(isset($taxons) && $taxons->count() != 0):?>
  <ul>
    <?php foreach($taxons as $taxa):?>
      <li><?php echo $taxa->getName();?> <?php echo link_to('(e)','taxonomy/edit?id='.$taxa->getId());?></li>
    <?php endforeach;?>
  </ul>
<?php else:?>
  <?php echo __('No Taxa Matching');?>
<?php endif;?>