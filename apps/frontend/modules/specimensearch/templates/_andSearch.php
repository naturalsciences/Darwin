<tr class="tag_line">
  <td colspan="3">
    <?php echo $form['tag'];?>
    <div class="purposed_tags">
    </div>
  </td>
  <td class="widget_row_delete">
    <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop id=clear_tag_'.$row_line); ?>
  </td>
</tr>
<script  type="text/javascript">
  $('#clear_tag_<?php echo $row_line;?>').click(function(){
    if($(this).closest('tbody').find('tr.tag_line').length == 1)
    {
      $(this).closest('tr').find('td input').val('');
    }
    else
      $(this).closest('tr').remove();
  });
</script>
