<?php slot('title', __('Edit Institution'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'people_institution','eid'=> $form->getObject()->getId())); ?>
                                                                                               
<div class="page">
  <h1 class="edit_mode">Edit Institution</h1>
  <?php include_partial('form', array('form' => $form)) ?>

 <ul class="board_col one_col encod_screen">
<?php foreach($widgets as $id => $widget):?>
  <?php if(!$widget->getVisible()) continue;?>
  <?php include_partial('widgets/wlayout', array(
	'widget' => $widget->getGroupName(),
	'is_opened' => $widget->getOpened(),
	'category' => 'peoplewidget',
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'institution')
	)); ?>
<?php endforeach;?>
</ul>

</div>