<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('expedition/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          &nbsp;<a href="<?php echo url_for('expedition/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to(__('Delete'), 'expedition/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
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
        <th><?php echo $form['expedition_from_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_from_date']->renderError() ?>
          <?php echo $form['expedition_from_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_to_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_to_date']->renderError() ?>
          <?php echo $form['expedition_to_date'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
