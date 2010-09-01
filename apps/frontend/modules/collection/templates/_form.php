<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<script type="text/javascript">
$(document).ready(function () 
{
  $('body').catalogue({});

  $("#collections_institution_ref").change(function() {
      $.get("<?php echo url_for('collection/completeOptions');?>/institution/"+$(this).val(), function (data) {
	      $("#collections_parent_ref").html(data);
      });
    });
  $("#collections_main_manager_ref").change(function() {
	  ref_element_id = $(this).val();
	  $info = 'good' ;
	  $('.collections_rights tbody tr').each(function() {
	      if($(this).attr('id') == ref_element_id) $info = 'bad' ;
	  });
	  if($info == 'good') addCollRightValue(ref_element_id);
  });
});
</script>
<form class="edition" action="<?php echo url_for('collection/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
<?php echo $form->renderGlobalErrors() ?>
  <table>
    <tbody>
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
      	<td colspan="2">
      	<table class="encoding collections_rights">
		  <thead>
		    <tr>
			 <th><label><?php echo __("User") ; ?></label></th>
			 <?php if(!$form->getObject()->isNew()) : ?>
			 <th><?php echo __('Set rights for sub-collections');?></th>
			 <?php endif ; ?>
			 <th>&nbsp;</th>
		    </tr>
		  </thead>
		  <tbody>
		    <?php foreach($form['CollectionsRights'] as $form_value):?>
			 <?php include_partial('coll_rights', array('form' => $form_value, 'ref_id' => ($form->getObject()->isNew() ? '':$form->getObject()->getId())));?>
		    <?php endforeach;?>
		    <?php foreach($form['newVal'] as $form_value):?>
			 <?php include_partial('coll_rights', array('form' => $form_value, 'ref_id' => ($form->getObject()->isNew() ? '':$form->getObject()->getId())));?>
		    <?php endforeach;?>
		  </tbody>
		</table>
		<div class='add_value'>
		  <a href="<?php echo url_for('collection/addValue'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" class="hidden"></a>
		  <a class='coll_right' href="<?php echo url_for('user/choose');?>/num/"><?php echo __('Add User');?></a>
		</div>
      	</td>
      <tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form['id'] ?>
          <?php if (!$form->getObject()->isNew()): ?>
      	    <a href="<?php echo url_for('collection/new') ?>"><?php echo __('New collection');?></a>
      	    &nbsp;<?php echo link_to(__('Duplicate collection'), 'collection/new?duplicate_id='.$form->getObject()->getId()) ?>
            &nbsp;<?php echo link_to('Delete', 'collection/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
          <?php endif; ?>
          &nbsp;<a href="<?php echo url_for('collection/index') ?>"><?php echo __('Cancel');?></a>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<script  type="text/javascript">
$(document).ready(function () {
    $('.clear_coll').live('click', detachCollRightValue);
  });
</script>
