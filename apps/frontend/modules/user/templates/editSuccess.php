<?php slot('Title', __('Edit Profile'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'users','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>                                                                                              
<div class="page">
  <h1 class="edit_mode">Profile for <?php echo($form['family_name']->getValue().' '.$form['given_name']->getValue()) ; ?></h1>
  <form class="edition" action="<?php echo url_for('user/edit') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <input type="hidden" name="id" value="<?php echo $form->getObject()->getId() ?>">
  <table>
  <tbody>
  <tr>
    	 <th><?php echo $form['db_user_type']->renderLabel() ?></th>
	 <td>
	      <?php echo $form['db_user_type']->renderError() ?>
	      <?php echo $form['db_user_type'] ?>
	 </td>
  </tr>
  <?php include_partial('profile', array('form' => $form)) ?>
  <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'userswidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'users')
	)); ?>
</div>
