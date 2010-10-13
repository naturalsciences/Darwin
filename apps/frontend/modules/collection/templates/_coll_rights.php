  <tr id="<?php echo $form['user_ref']->getValue() ;?>">
    <td>
      <?php echo $form['user_ref']->renderError(); ?>
      <?php echo $form['user_ref'];?>
      <?php echo $form['user_ref']->renderLabel();?>
    </td>
    <?php if ($ref_id != '') : ?>
    <td class='set_rights'>
	 <a id="subcol" class='set_rights' href="<?php echo url_for('collection/rights?user_ref='.$form['user_ref']->getValue().'&collection_ref='.$ref_id);?>" name="<?php echo __('sub collections') ; ?>"><?php echo __('Set rights');?></a>
    </td>
    <?php endif ; ?>
    <?php if ($reg_widget != '') : ?>
    <td class='set_rights'>
	 <a id="widget" class='set_rights' href="<?php echo url_for('collection/widgetsRight?user_ref='.$form['user_ref']->getValue().'&collection_ref='.$reg_widget);?>" name="<?php echo __('Widgets') ; ?>"><?php echo __('Allow');?></a>
    </td>
    <?php endif ; ?>    
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_coll'); ?>
    </td>    
  </tr>
