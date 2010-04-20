  <tr>
    <td><?php echo $form['id'];?>
      <?php echo $form['code_category']->renderError(); ?>
      <?php echo $form['code_category'];?>
    </td>
    <td>
      <?php echo $form['code']->renderError(); ?>
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
    <td colspan = '2' class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
    </td>    
  </tr>