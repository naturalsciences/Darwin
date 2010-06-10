  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="5">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr>
    <td>
      <?php echo $form['accompanying_type'];?>
    </td>
    <td>
      <?php echo $form['form'];?>
    </td>
    <td>
      <?php echo $form['quantity'];?>
    </td>
    <td>
      <?php echo $form['unit'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_code'); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
  <tr class="biological">
    <td class="left_tabed">
      <?php echo $form['taxon_ref']->renderLabel();?>
    </td>
    <td colspan="4">
      <?php echo $form['taxon_ref'];?>
    </td>
  </tr>
  <tr class="mineral hidden">
    <td class="left_tabed">
      <?php echo $form['mineral_ref']->renderLabel();?>
    </td>
    <td colspan="4">
      <?php echo $form['mineral_ref'];?>
    </td>
  </tr>