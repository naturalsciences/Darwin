<?php slot('title', __('Edit User'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'user','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
                                                                                               
<div class="page">
  <h1 class="edit_mode">Edit User</h1>
  <?php include_partial('form', array('form' => $form)) ?>

  <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'userswidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'users')
	)); ?>
</div>
