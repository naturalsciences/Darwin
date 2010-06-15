<tbody id="code_<?php echo $rownum;?>">
  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="8">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr>
    <td>
      <?php echo $form['code_category'];?>
    </td>
    <td>
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
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_code id=clear_code_'.$rownum); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
</tbody>
<script type="text/javascript">
  $(document).ready(function () {
    $("#clear_code_<?php echo $rownum;?>").click( function()
    {
      parent = $(this).closest('tbody');
      nvalue='';
      $(parent).find('input[id$=\"_code_prefix\"]').val(nvalue);
      $(parent).find('input[id$=\"_code\"]').val(nvalue);
      $(parent).find('input[id$=\"_code_suffix\"]').val(nvalue);
      $(parent).find('input[id$=\"_deleted\"]').val(1);
      $(parent).hide();
      visibles = $(parent).closest('table.property_values').find('tbody:visible').size();
      if(!visibles)
      {
        $(this).closest('table.property_values').find('thead').hide();
      }
    });
  });
</script>