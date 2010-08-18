<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('mineralogy/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="classifications_edit">
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['code']->renderLabel() ?></th>
        <td>
          <?php echo $form['code']->renderError() ?>
          <?php echo $form['code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
	<td rowspan="8" class="keyword_row">
	      <?php include_partial('catalogue/keywordsView', array('form' => $form,'table_name' => 'mineralogy','field_name' => 'mineralogy_name')); ?>
	</td>
      </tr>
      <tr>
	<th></th>
	<td>
	   <?php include_partial('catalogue/keywordsList');?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['classification']->renderLabel() ?></th>
        <td>
          <?php echo $form['classification']->renderError() ?>
          <?php echo $form['classification'] ?>
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
        <th class="top_aligned"><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['parent_ref']->renderError() ?>
          <?php echo $form['parent_ref'] ?>
	  <div class="warn_message ref_name button hidden" id="taxonomy_parent_ref_warning"><?php echo __('The parenty does not follow the possible upper level rule');?></div>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['formule']->renderLabel() ?></th>
        <td>
          <?php echo $form['formule']->renderError() ?>
          <?php echo $form['formule'] ?>
        </td>
      </tr>
      <tr id="parent_ref">
        <th class="ref_name"><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['cristal_system']->renderError() ?>
          <?php echo $form['cristal_system'] ?>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New Unit'), 'mineralogy/new') ?>
            &nbsp;<?php echo link_to(__('Duplicate Unit'), 'mineralogy/new?duplicate_id='.$form->getObject()->getId()) ?>            
          <?php endif?>

          <?php echo $form['id']->render() ?><?php echo $form['table']->render() ?><?php echo link_to('', 'catalogue/searchPUL', array('id' => 'searchPUL', 'class' => 'hidden'));?>  &nbsp;<a href="<?php echo url_for('mineralogy/index') ?>"><?php echo __('Cancel');?></a>

          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to(__('Delete'), 'mineralogy/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
          <?php endif; ?>

          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
