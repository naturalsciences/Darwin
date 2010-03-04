<?php slot('title', __('Edit Gtu'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'gtu','eid'=> $form->getObject()->getId())); ?>
                                                                                               
<div class="page">
  <h1 class="edit_mode"><?php echo __('Edit Gtu');?></h1>
  <?php include_partial('form', array('form' => $form)) ?>

 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'gtu',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'gtu')
	)); ?>

</div>