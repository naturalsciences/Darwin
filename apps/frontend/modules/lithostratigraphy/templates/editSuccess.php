<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_lithostratigraphy','eid'=> $form->getObject()->getId())); ?>
<?php slot('title', __('Edit Lithostratigraphic unit'));  ?>
<div class="page">
    <h1 class="edit_mode"><?php echo __('Edit Lithostratigraphic unit');?></h1>

    <?php include_partial('form', array('form' => $form)); ?>

 <ul class="board_col one_col encod_screen">
<?php foreach($widgets as $id => $widget):?>
  <?php if(!$widget->getVisible()) continue;?>
  <?php include_partial('widgets/wlayout', array(
	'widget' => $widget->getGroupName(),
	'is_opened' => $widget->getOpened(),
	'category' => 'cataloguewidget',
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'lithostratigraphy')
	)); ?>
<?php endforeach;?>
</ul>

</div>