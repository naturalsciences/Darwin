<?php include_javascripts_for_form($form) ?>
<div id="lang_screen">
<form class="edition qtiped_form" action="<?php echo url_for('account/lang?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="lang_form">
<?php echo $form['users_ref'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['language_country']->renderLabel();?></th>
      <td>
        <?php echo $form['language_country']->renderError(); ?>
        <?php echo $form['language_country'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['mother']->renderLabel();?></th>
      <td>
        <?php echo $form['mother']->renderError(); ?>
        <?php echo $form['mother'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['preferred_language']->renderLabel();?></th>
      <td>
        <?php echo $form['preferred_language']->renderError(); ?>
        <?php echo $form['preferred_language'];?>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	   <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=users_languages&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
	     <?php echo __('Delete');?>
	  </a>
	<?php endif;?>
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>

</form>

</div>