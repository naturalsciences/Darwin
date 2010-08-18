<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('institution/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th class="top_aligned"><?php echo $form['family_name']->renderLabel('Name') ?></th>
        <td>
          <?php echo $form['family_name']->renderError() ?>
          <?php echo $form['family_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['additional_names']->renderLabel('Abbreviation') ?></th>
        <td>
          <?php echo $form['additional_names']->renderError() ?>
          <?php echo $form['additional_names'] ?>
        </td>
      </tr>
      <tr>
        <th class="top_aligned"><?php echo $form['sub_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['sub_type']->renderError() ?>
          <?php echo $form['sub_type'] ?>
        </td>
      </tr>
      <tr>
        <th class="top_aligned"><?php echo $form['db_people_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['db_people_type']->renderError() ?>
          <?php echo $form['db_people_type'] ?>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(true) ?>

          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New Institution'), 'institution/new') ?>
            &nbsp;<?php echo link_to(__('Duplicate Institution'), 'institution/new?duplicate_id='.$form->getObject()->getId()) ?>            
          <?php endif?>

          &nbsp;<a href="<?php echo url_for('institution/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'institution/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
