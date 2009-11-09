<ul>
<?php foreach($items as $i=>$item):?>
  <li style="margin-left: <?php echo $i;?>em">
    <?php if($i != 0):?>
      <?php echo image_tag('tree_spacer.gif');?>
    <?php endif;?>
    <?php echo $item->getName();?>
  </li>
<?php endforeach;?>
</ul>
