<?php slot('title', __('My Profile'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'users', 'eid' => $sf_user->getAttribute('db_user_id'))); ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<div class="page">
  <h1 class="edit_mode">Edit My Profile</h1>
  <form class="edition" action="<?php echo url_for('user/profile') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
  <tbody>
  <?php include_partial('profile', array('form' => $form)) ?>
  <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'userswidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'users')
	)); ?>
</div>
