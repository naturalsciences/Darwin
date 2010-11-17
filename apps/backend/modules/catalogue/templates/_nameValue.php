  <td class="values">
      <?php echo $form->renderHiddenFields();?>
    <?php echo $form['keyword']->getValue();?>
  </td>
  <td class="widget_row_delete">
  <?php if(!$view) : ?>
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
  <?php endif ?>
  </td>
