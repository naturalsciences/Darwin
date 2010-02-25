<?php slot('title', __('Edit Institution'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'people_institution','eid'=> $form->getObject()->getId())); ?>
                                                                                               
<div class="page">
  <h1 class="edit_mode">Edit Institution</h1>
  <?php include_partial('form', array('form' => $form)) ?>

 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'peoplewidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'institution')
	)); ?>

</div>