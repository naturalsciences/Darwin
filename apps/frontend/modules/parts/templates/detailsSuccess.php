<?php slot('title', __('Edit Parts Details'));  ?>
<?php use_stylesheet('widgets.css') ?>
<?php use_javascript('widgets.js') ?>
<?php use_javascript('catalogue.js') ?>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen_parts','eid'=> $parts->getId(), 'table' => 'specimen_parts')); ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual, 'part' => $parts) );?>

  <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'specimenwidget',
	'columns' => 2,
	'options' => array('eid' =>  $parts->getId(), 'table' => 'specimen_parts')
	)); ?>

<?php include_partial('specimen/specAfterTab');?>

