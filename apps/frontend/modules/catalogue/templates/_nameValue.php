<tr class="<?php if($form->getObject()->isNew()):?>new_record<?php endif;?>">
  <td>
      <?php echo $form->renderHiddenFields();?>
      <?php echo $form->getObject()->getKeywordType();?> : <?php echo $form->getObject()->getKeyword();?>
  </td>
  <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
  </td>    
</tr>
