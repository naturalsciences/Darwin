  <tr id="<?php echo $form['user_ref']->getValue() ;?>">
    <td>
      <?php echo $form['user_ref']->renderError(); ?>
      <?php echo $form['user_ref'];?>
      <?php echo $form['user_ref']->renderLabel();?>
    </td>
    <?php if ($ref_id != '') : ?>
    <td class='set_rights'>
	 <a class='set_rights' href="<?php echo url_for('collection/rights?user_ref='.$form['user_ref']->getValue().'&collection_ref='.$ref_id);?>"><?php echo __('Set rights');?></a>
    </td>
    <?php endif ; ?>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_coll'); ?>
    </td>    
  </tr>
