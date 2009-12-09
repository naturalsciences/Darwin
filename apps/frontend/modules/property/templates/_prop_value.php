<?php echo $form['id'];?>
<table>
  <tr>
    <td><?php echo $form['property_value']->renderLabel();?></td>
    <td>
      <?php echo $form['property_value']->renderError(); ?>
      <?php echo $form['property_value'];?>
    </td>
    <td><?php echo $form['property_accuracy']->renderLabel();?></td>
    <td>
      <?php echo $form['property_accuracy']->renderError(); ?>
      <?php echo $form['property_accuracy'];?>
    </td>
  </tr>
</table>