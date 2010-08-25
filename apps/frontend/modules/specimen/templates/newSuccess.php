<?php slot('title', __( $form->isNew() ? 'Add Specimens' : 'Edit Specimen'));  ?>

<script type="text/javascript">
$(document).ready(function ()
{
    $('.widget .widget_content:hidden .error_list:has(li)').each(function(){
        showWidgetContent($(this).closest('.widget'));
    });
    
    $('.spec_error_list li.hidden').each(function(){
        field = getElInClasses($(this),'error_fld_');
        if( $('#specimen_'+field).length == 0 )
            $(this).show();
    });
});
</script>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>

<?php include_partial('specBeforeTab', array('specimen' => $form->getObject(),'form'=> $form, 'mode'=>'specimen_edit') );?>
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>

  <div>
    <ul id="error_list" class="error_list" style="display:none">
      <li></li>
    </ul>
  </div>
	<form action="<?php echo url_for('specimen/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : ''). ($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() )) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
		<div>
			<?php echo $form['id']->render() ?>
			<?php if($form->hasGlobalErrors()):?>
				<ul class="spec_error_list">
					<?php foreach ($form->getErrorSchema()->getErrors() as $name => $error): ?>
						<li class="error_fld_<?php echo $name;?>"><?php echo __($error) ?></li>
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
		<p class="form_buttons">
		  <?php if (!$form->getObject()->isNew()): ?>
		    <?php echo link_to(__('New specimen'), 'specimen/new') ?>
		    &nbsp;<a href="<?php echo url_for('specimen/new?duplicate_id='.$form->getObject()->getId());?>"><?php echo __('Duplicate specimen');?></a>
		    &nbsp;<a href="<?php echo url_for('catalogue/deleteRelated?table=specimens&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>" id="spec_delete"><?php echo __('Delete');?></a>
		  <?php endif?>
		  &nbsp;<a href="<?php echo url_for('specimensearch/index') ?>" id="spec_cancel"><?php echo __('Cancel');?></a>
		  <input type="submit" value="<?php echo __('Save');?>" id="submit_spec_f1"/>
		</p>
	</form>
<script  type="text/javascript">

function addError(html)
{
  $('ul#error_list').find('li').text(html);
  $('ul#error_list').show();
}

function removeError()
{
  $('ul#error_list').hide();
  $('ul#error_list').find('li').text(' ');
}

$(document).ready(function () {
  $("a#spec_delete").click(function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError();
       $.ajax({
               url: $(this).attr('href'),
               success: function(html) {
		      if(html == "ok" )
		      {
			// Reload page
			$(location).attr('href',$('a#spec_cancel').attr('href'));
		      }
		      else
		      {
			addError(html); //@TODO:change this!
		      }
		},
               error: function(xhr){
		  addError('Error!  Status = ' + xhr.status);
               }
             }
            );
    }
    return false;
  });
});
</script>
<?php include_partial('specAfterTab', array('specimen'=> $form->getObject()) );?>
