  <tr>
    <td colspan="2"><?php echo $form['id'];?>
      <?php echo $form['name']->renderError(); ?>
      <?php echo $form['name'];?>
    </td>
    <td colspan="2">
      <?php echo $form['country_language_full_text']->renderError(); ?>
      <?php echo $form['country_language_full_text'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
    </td>    
  </tr>