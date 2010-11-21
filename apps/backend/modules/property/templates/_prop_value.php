  <tr>
    <td colspan="2"><?php echo $form['id'];?>
      <?php echo $form['property_value']->renderError(); ?>
      <?php echo $form['property_value'];?>
    </td>
    <td colspan="2">
      <?php echo $form['property_accuracy']->renderError(); ?>
      <?php echo $form['property_accuracy'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
    </td>    
  </tr>