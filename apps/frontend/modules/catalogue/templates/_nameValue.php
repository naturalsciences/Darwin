<tr>
  <td>
      <?php echo $form->renderHiddenFields();?>
      <?php echo ClassificationKeywords::getTagNameFor($form['keyword_type']->getValue());?>
  </td>
  <td>
    <?php echo $form['keyword']->getValue();?>
  </td>
  <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
  </td>    
</tr>
