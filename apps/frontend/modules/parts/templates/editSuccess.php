<?php slot('title', __( $form->isNew() ? 'Add Part' : 'Edit part'));  ?>
<?php use_stylesheet('widgets.css') ?>
<?php use_javascript('widgets.js') ?>
<?php use_javascript('catalogue.js') ?>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'part','eid'=> $part->getId(), 'table' => 'specimen_parts')); ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual, 'part'=> $part ,'mode' => 'parts_edit') );?>

  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>


	<form action="<?php echo url_for('parts/edit'. ($form->isNew() ? '?indid='.$individual->getId() : '?id='.$form->getObject()->getId()));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
		<div>
			<?php if($form->hasGlobalErrors()):?>
				<ul class="spec_error_list">
					<?php foreach ($form->getGlobalErrors() as $name => $error): ?>
						<li><?php echo __($name." ".$error); ?></li>
					<?php endforeach; ?>
					<?php foreach ($form->getErrorSchema()->getErrors() as $name => $error): ?>
						<li class="error_fld_<?php echo $name;?>"><?php echo __($name." ".$error) ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif;?>

  <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'partwidget',
	'columns' => 2,
	'options' => array('form' => $form)
	)); ?>
			<p class="clear"></p>
			<p>
			  <input type="submit" value="<?php echo __('Submit');?>" id="submit_spec_f1"/>
			</p>
<?php include_partial('specimen/specAfterTab');?>

