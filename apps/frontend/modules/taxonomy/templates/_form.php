<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('taxonomy/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['level_ref']->renderError() ?>
          <?php echo $form['level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['status']->renderLabel() ?></th>
        <td>
          <?php echo $form['status']->renderError() ?>
          <?php echo $form['status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['extinct']->renderLabel() ?></th>
        <td>
          <?php echo $form['extinct']->renderError() ?>
          <?php echo $form['extinct'] ?>
        </td>
      </tr> 
      <tr>
        <th><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['parent_ref']->renderError() ?>
          <?php echo $form['parent_ref'] ?>
        </td>
      </tr>
    </tbody>
  </table>

<fieldset>
    <legend><?php echo __('Recombination'); ?> <?php echo $form['recombination_1']['enabled'] ?></legend>     
      <?php echo $form['recombination_1']['enabled']->renderError() ?>
      <table class="recombination">
	<tr><td>
<?php echo $form['recombination_1']['record_id_2']->renderError() ?>
<?php echo $form['recombination_1']['record_id_2']->renderLabel(__('Of')); ?></td>
	<td><?php echo $form['recombination_1']['record_id_2'] ?></td></tr>

	<tr><td>
<?php echo $form['recombination_2']['record_id_2']->renderError() ?><?php echo $form['recombination_2']['record_id_2']->renderLabel(__('With')); ?></td>
	<td><?php echo $form['recombination_2']['record_id_2'] ?></td></tr>
      </table>
</fieldset>


<fieldset>
     <legend><?php echo __('Renamed'); ?> <?php echo $form['current_name']['enabled']; ?></legend>
      <?php echo $form['current_name']['enabled']->renderError() ?>
      <ul>
	<li>
<?php echo $form['current_name']['record_id_2']->renderError() ?>
<?php echo $form['current_name']['record_id_2']->renderLabel(__('Current Name')); ?>
	<?php echo $form['current_name']['record_id_2'] ?></li>
      </ul>
</fieldset>


<?php echo $form['id']->render() ?>  &nbsp;<a href="<?php echo url_for('taxonomy/index') ?>"><?php echo __('Cancel');?></a>
<?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'taxonomy/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
<?php endif; ?>
<input type="submit" value="<?php echo __('Save');?>" />
</form>

<script>
$('#taxonomy_recombination_1_enabled:not(:checked)').parent().parent().find('.recombination').hide()
$('#taxonomy_recombination_1_enabled').click(function ()
{
  $('.recombination').toggle();
});


$('#taxonomy_current_name_enabled:not(:checked)').parent().parent().find('.#taxonomy_current_name_record_id_2').parent().hide();
$('#taxonomy_current_name_enabled').click(function ()
{
  $('#taxonomy_current_name_record_id_2').parent().toggle();
});
</script>