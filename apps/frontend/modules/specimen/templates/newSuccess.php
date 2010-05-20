<?php slot('title', __( $form->isNew() ? 'Add Specimens' : 'Edit Specimen'));  ?>

<script type="text/javascript">
$(document).ready(function ()
{
    $('.widget .widget_content:hidden .error_list:has(li)').each(function(){
        showWidgetContent($(this).closest('.widget'));
    });
    
    $('.spec_error_list li.hidden').each(function(){
        console.log($(this));
        field = getElInClasses($(this),'error_fld_');
        console.log(field);
        if( $('#specimen_'+field).length == 0 )
            $(this).show();
    });
});
</script>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>

<?php include_partial('specBeforeTab', array('specimen' => $form->getObject(),'form'=> $form) );?>
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>

	<form action="<?php echo url_for('specimen/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : ''). ($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() )) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
		<div>
			<?php //echo $form->renderHiddenFields() ?>
			<?php echo $form['id']->render() ?>
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
			  'category' => 'specimenwidget',
			  'columns' => 2,
			  'options' => array('form' => $form),
			)); ?>

			</div>
			<p class="clear"></p>
			<p>
			  <input type="submit" value="<?php echo __('Submit');?>" id="submit_spec_f1"/>
			</p>
		</form>
<?php include_partial('specAfterTab');?>
