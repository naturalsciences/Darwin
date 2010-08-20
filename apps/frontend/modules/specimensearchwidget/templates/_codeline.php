<tr>
  <td><?php echo $code['category'];?></td>
  <td><?php echo $code['code_part'];?></td>
  <td class="and_col">
    <?php echo link_to(image_tag('next.png'),'specimen/index', array('class'=>'code_between next'));?>
    <?php echo link_to(image_tag('previous.png'),'specimen/index', array('class'=>'code_between hidden prev'));?>
  </td>
  <td class="between_col"><?php echo $code['code_from'];?></td>
  <td class="between_col"><?php echo $code['code_to'];?></td>
  <td class="widget_row_delete">
    <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
  </td>
</tr>