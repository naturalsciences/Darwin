<tr alt="<?php echo $form['group_name']->getValue();?>">
  <th>

    <?php echo $form['group_name'];?>
    <?php echo __(TagGroups::getGroup($form['group_name']->getValue()));?>
  </th>
  <td>
    <?php echo $form['id'];?>
    <?php echo $form['id']->renderError(); ?>
    <?php echo $form['group_name']->renderError(); ?>

    <?php echo $form['sub_group_name']->renderError(); ?>
    <?php echo $form['sub_group_name'];?>
  </td>
  <td>
    <?php echo $form['tag_value']->renderError(); ?>
    <?php echo $form['tag_value'];?>
  </td>
  <td class="widget_row_delete">
    <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
  </td>    
</tr>