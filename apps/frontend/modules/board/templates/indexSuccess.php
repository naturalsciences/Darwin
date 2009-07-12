<?php slot('title', __('Dashboard'));  ?>
<?php include_partial('boardwidget/list') ?>
<div class="board">
  <ul class="board_col">
    <?php $changed_col=false;?>
    <?php foreach($widgets as $id => $widget):?>
      <?php if($changed_col==false && $widget->getColNum()==2):?>
	    <?php $changed_col=true;?>
	      </ul>
	      <div class="board_spacer">&nbsp;</div>
	      <ul class="board_col">
      <?php endif;?>
	  <?php include_partial('boardwidget/wlayout',array('widget' => $widget->getGroupName(),'is_opened' => $widget->getOpened())) ?>
    <?php endforeach;?>
  </ul>
</div>