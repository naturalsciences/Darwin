<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<script type="text/javascript">
$(document).ready(function () 
{
    $("#collections_institution_ref").change(function() {
      $.get("<?php echo url_for('collection/completeOptions');?>/institution/"+$(this).val(), function (data) {
	$("#collections_parent_ref").html(data);
      });
    });
});
</script>
<form class="edition" action="<?php echo url_for('collection/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
        <th><?php echo $form['institution_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['institution_ref']->renderError() ?>
          <?php echo $form['institution_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_type']->renderError() ?>
          <?php echo $form['collection_type'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['code']->renderLabel() ?></th>
        <td>
          <?php echo $form['code']->renderError() ?>
          <?php echo $form['code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['main_manager_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['main_manager_ref']->renderError() ?>
          <?php echo $form['main_manager_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['parent_ref']->renderError() ?>
          <?php echo $form['parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['code_auto_increment']->renderLabel() ?></th>
        <td>
          <?php echo $form['code_auto_increment']->renderError() ?>
          <?php echo $form['code_auto_increment'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['code_part_code_auto_copy']->renderLabel() ?></th>
        <td>
          <?php echo $form['code_part_code_auto_copy']->renderError() ?>
          <?php echo $form['code_part_code_auto_copy'] ?>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          &nbsp;<a href="<?php echo url_for('collection/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'collection/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
