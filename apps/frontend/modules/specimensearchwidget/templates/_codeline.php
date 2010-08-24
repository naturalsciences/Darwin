<tr>
  <td><?php echo $code['category'];?></td>
  <td><?php echo $code['code_part'];?></td>
  <td class="and_col">
    <?php echo link_to(image_tag('next.png'),'specimen/index', array('class'=>'code_between next'));?>
    <?php echo link_to(image_tag('previous.png'),'specimen/index', array('class'=>'code_between hidden prev'));?>
  </td>
  <td class="between_col"><?php echo $code['code_from'];?></td>
  <td class="between_col"><?php echo $code['code_to'];?></td>
  <td>
    <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop id=clear_code_'.$row_line); ?>
  </td>
</tr>
<script  type="text/javascript">
  $('#clear_code_<?php echo $row_line;?>').click(function(event)
  {
    event.preventDefault();
    if($(this).closest('tbody').find('tr').length == 2)
    {
      $(this).closest('tr').find('td input').val('');
    }
    else
    {
      $(this).closest('tr').remove();
    }
    checkBetween();
  });
</script>
