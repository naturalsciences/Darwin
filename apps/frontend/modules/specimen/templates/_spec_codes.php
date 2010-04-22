  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="8">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr>
    <td>
      <?php echo $form['code_category'];?>
    </td>
    <td>
      <?php echo $form['code_prefix'];?>
    </td>
    <td>
      <?php echo $form['code_prefix_separator'];?>
    </td>
    <td>
      <?php echo $form['code'];?>
    </td>
    <td>
      <?php echo $form['code_suffix_separator'];?>
    </td>
    <td>
      <?php echo $form['code_suffix'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>