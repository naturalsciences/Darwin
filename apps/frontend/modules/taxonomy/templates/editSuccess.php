<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
<?php slot('title', __('Edit Taxonomic unit'));  ?>
<div class="page">
    <h1><?php echo __('Edit Taxonomic unit');?></h1>

    <?php include_partial('form', array('form' => $form)); ?>


<?php use_helper('Javascript') ?>
<?php echo javascript_tag("
var chgstatus_url='".url_for('widgets/changeStatus?category=catalogue')."';
var chgorder_url='".url_for('widgets/changeOrder?category=catalogue')."';
var reload_url='".url_for('widgets/reloadContent?category=catalogue&eid='.$form->getObject()->getId())."';
");?>
 <ul class="board_col one_col encod_screen">
<?php foreach($widgets as $id => $widget):?>
  <?php if(!$widget->getVisible()) continue;?>
  <?php include_partial('widgets/wlayout', array(
	'widget' => $widget->getGroupName(),
	'is_opened' => $widget->getOpened(),
	'category' => 'cataloguewidget',
	'options' => array('eid' => $form->getObject()->getId())
	)); ?>
<?php endforeach;?>
</ul>

</div>