  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="4">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr>
    <td>
      <?php echo $form['insurance_year'];?>
    </td>
    <td>
      <?php echo $form['insurance_value'];?>
    </td>
    <td>
      <?php echo $form['insurance_currency'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_code'); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
  <tr>
    <td class="left_tabed">
      <?php echo $form['insurer_ref']->renderLabel();?>
    </td>
    <td colspan="3">
      <?php echo $form['insurer_ref'];?>
    </td>
  </tr>