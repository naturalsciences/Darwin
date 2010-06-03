<?php slot('title', __( $individual->getObject()->isNew() ? 'Add specimen individual' : 'Edit specimen individual'));  ?>

<?php use_stylesheet('widgets.css') ?>
<?php use_javascript('widgets.js') ?>
<?php use_javascript('catalogue.js') ?>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'individuals', 'table' => 'specimen_individuals', 'eid'=> ($individual->getObject()->isNew() ?null: $individual->getObject()->getId()))); ?>
<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual->getObject(), 'mode'=>'individual_edit'));?>

<?php include_stylesheets_for_form($individual) ?>
<?php include_javascripts_for_form($individual) ?>

<form action="<?php echo url_for('individuals/edit?spec_id='.$specimen->getId().(($individual->getObject()->isNew())?'':'&individual_id='.$individual->getObject()->getId())); ?>" method="post" <?php $individual->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <div>
    <?php echo $individual['id']->render(); ?>
    <?php echo $individual['specimen_ref']->render(); ?>
    <?php if($individual->hasGlobalErrors()):?>
      <ul class="spec_error_list">
      <?php foreach ($individual->getGlobalErrors() as $name => $error): ?>
	<li><?php echo __($name." ".$error); ?></li>
      <?php endforeach; ?>
      <?php foreach ($individual->getErrorSchema()->getErrors() as $name => $error): ?>
	<li class="error_fld_<?php echo $name;?>"><?php echo __($name." ".$error) ?></li>
      <?php endforeach; ?>
      </ul>
    <?php endif;?>

    <?php include_partial('widgets/screen', array(
			  'widgets' => $widgets,
			  'category' => 'individualswidget',
			  'columns' => 2,
			  'options' => array('form' => $individual),
			)); ?>

  </div>
  <p class="clear"></p>
  <p><input type="submit" value="<?php echo __('Submit');?>" id="submit_spec_individual_f1"/></p>
</form>

<?php include_partial('specimen/specAfterTab');?>
