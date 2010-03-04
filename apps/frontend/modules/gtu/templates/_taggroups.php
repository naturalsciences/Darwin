<tr>
  <td>
    <?php echo $form['id'];?>
    <?php echo $form['group_name']->renderError(); ?>
    <?php echo $form['group_name'];?>
    <?php echo $form['group_name']->getValue();?>
  </td>
  <td>
    <?php echo $form['sub_group_name']->renderError(); ?>
    <?php echo $form['tag_value']->renderError(); ?>

    <?php echo $form['sub_group_name'];?>

    <?php echo $form['tag_value'];?>
  </td>
  <td class="widget_row_delete">
    <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
  </td>    
</tr>