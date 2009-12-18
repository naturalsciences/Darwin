  <tr>
    <td><?php echo $form['id'];?>
      <?php echo $form['property_value']->renderError(); ?>
      <?php echo $form['property_value'];?>
    </td>
    <td>
      <?php echo $form['property_accuracy']->renderError(); ?>
      <?php echo $form['property_accuracy'];?>
    </td>
    
    <td>
      <?php echo image_tag('widget_help_close.png','alt=Delete class=clear_prop');?>
    </td>
  </tr>