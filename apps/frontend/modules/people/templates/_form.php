<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('people/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
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
        <th><?php echo $form['gender']->renderLabel() ?></th>
        <td>
          <?php echo $form['gender']->renderError() ?>
          <?php echo $form['gender'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['birth_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['birth_date']->renderError() ?>
          <?php echo $form['birth_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['end_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['end_date']->renderError() ?>
          <?php echo $form['end_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['activity_date_from']->renderLabel() ?></th>
        <td>
          <?php echo $form['activity_date_from']->renderError() ?>
          <?php echo $form['activity_date_from'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['activity_date_to']->renderLabel() ?></th>
        <td>
          <?php echo $form['activity_date_to']->renderError() ?>
          <?php echo $form['activity_date_to'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['db_people_type']->renderLabel('Type') ?></th>
        <td>
          <?php echo $form['db_people_type']->renderError() ?>
          <?php echo $form['db_people_type'] ?>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>

          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New People'), 'people/new') ?>
          <?php endif?>

          &nbsp;<a href="<?php echo url_for('people/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'people/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>