<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('user/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['db_user_type']->renderLabel("Type") ?></th>
        <td>
          <?php echo $form['db_user_type']->renderError() ?>
          <?php echo $form['db_user_type'] ?>
        </td>
      </tr>
      <tr id="is_not_physical">
        <th><?php echo $form['sub_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['sub_type']->renderError() ?>
          <?php echo $form['sub_type'] ?>
        </td>
      </tr>
      <tr id="is_physical">
        <th><?php echo $form['title']->renderLabel() ?></th>
        <td>
          <?php echo $form['title']->renderError() ?>
          <?php echo $form['title'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['given_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['given_name']->renderError() ?>
          <?php echo $form['given_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['family_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['family_name']->renderError() ?>
          <?php echo $form['family_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['additional_names']->renderLabel() ?></th>
        <td>
          <?php echo $form['additional_names']->renderError() ?>
          <?php echo $form['additional_names'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['is_physical']->renderLabel() ?></th>
        <td>
          <?php echo $form['is_physical']->renderError() ?>
          <?php echo $form['is_physical'] ?>
        </td>
      </tr>
      <tr id="is_physical">
        <th><?php echo $form['gender']->renderLabel() ?></th>
        <td>
          <?php echo $form['gender']->renderError() ?>
          <?php echo $form['gender'] ?>
        </td>
      </tr>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'people/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<script>
$(document).ready(function () {
	$(':checkbox#users_is_physical').change(function(){
	if ($(this).attr("checked"))
	{
		$('tr#is_not_physical').hide();
		$('tr#is_physical').fadeIn();
		$('label[for="users_family_name"]').html("Family Name") ;
		$('label[for="users_given_name"]').html("Given Name") ;
	}
	else
	{
		$('tr#is_physical').hide();
		$('tr#is_not_physical').fadeIn();
		$('label[for="users_family_name"]').html("Name") ;
		$('label[for="users_given_name"]').html("Abbreviation") ;
    	}
	})  ;
	$(':checkbox#users_is_physical').change();
});
</script>
