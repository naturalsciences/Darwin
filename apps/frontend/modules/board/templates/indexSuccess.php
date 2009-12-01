<?php slot('title', __('Dashboard'));  ?>
<script type="text/javascript">
var chgstatus_url='<?php echo url_for('widgets/changeStatus?category=board');?>';
var chgorder_url='<?php echo url_for('widgets/changeOrder?category=board');?>';
var reload_url='<?php echo url_for('widgets/reloadContent?category=board');?>';
</script>
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'board')) ?>
<div class="board">
  <ul class="board_col">
    <?php $changed_col=false;?>
    <?php foreach($widgets as $id => $widget):?>
      <?php if(!$widget->getVisible()) continue;?>

      <?php if($changed_col==false && $widget->getColNum()==2):?>
	    <?php $changed_col=true;?>
	      </ul>
	      <div class="board_spacer">&nbsp;</div>
	      <ul class="board_col">
      <?php endif;?>
	  <?php include_partial('widgets/wlayout', array(
        'widget' => $widget->getGroupName(),
        'is_opened' => $widget->getOpened(),
        'category' => 'boardwidget',
	'options' => array(),
        )); ?>
    <?php endforeach;?>
    <?php if($changed_col==false):?>
      </ul>
      <div class="board_spacer">&nbsp;</div>
      <ul class="board_col">
      <?php endif;?>
  </ul>
</div>