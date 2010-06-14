<script type="text/javascript">
$(document).ready(function () {
    $('.treelist li:not(li:has(ul)) img.tree_cmd').hide();
    $('.collapsed').click(function()
    {
        $(this).hide();
        $(this).siblings('.expanded').show();
        $(this).parent().siblings('ul').show();
    });
    
    $('.expanded').click(function()
    {
        $(this).hide();
        $(this).siblings('.collapsed').show();
        $(this).parent().siblings('ul').hide();
    });
});
</script>
<div class="container">
  <?php foreach($institutions as $institution):?>
    <h2><?php echo $institution->getFormatedName();?></h2>
    <div class="treelist">
      <?php $prev_level = 0;?>
      <?php foreach($institution->Collections as $col_item):?>
        <?php if($prev_level < $col_item->getLevel()):?>
          <ul>
        <?php else:?>
          </li>
            <?php if($prev_level > $col_item->getLevel()):?>
              <?php echo str_repeat('</ul></li>',$prev_level-$col_item->getLevel());?>
            <?php endif;?>
        <?php endif;?>
        <li class="rid_<?php echo $col_item->getId();?>"><div class="col_name">
        <?php echo image_tag ('blue_expand.png', array('alt' => '+', 'class'=> 'tree_cmd collapsed'));?>
        <?php echo image_tag ('blue_expand_up.png', array('alt' => '-', 'class'=> 'tree_cmd expanded'));?>
        <span><?php echo $col_item->getName();?>
        <?php if(! $is_choose):?>
	  <?php echo link_to(image_tag('edit.png'),'collection/edit?id='.$col_item->getId());?>
        <?php endif;?></span></div>
        <?php $prev_level =$col_item->getLevel();?>
      <?php endforeach;?>
      <?php echo str_repeat('</li></ul>',$col_item->getLevel());?>
    </div>
  <?php endforeach;?>
  <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for('collection/new') ?>"><?php echo __('New');?></a></div>
</div>
