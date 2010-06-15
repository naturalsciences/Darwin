<tbody id="specimens_accompanying_<?php echo $rownum;?>">
  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="5">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr>
    <td>
      <?php echo $form['accompanying_type'];?>
    </td>
    <td>
      <?php echo $form['form'];?>
    </td>
    <td>
      <?php echo $form['quantity'];?>
    </td>
    <td>
      <?php echo $form['unit'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_code id=clear_accompanying_unit_'.$rownum); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
  <tr class="accompanying_unit" style="<?php echo ($form['accompanying_type']->getValue()=='mineral')?'display: none;':''?>">
    <td class="left_tabed">
      <?php echo $form['taxon_ref']->renderLabel();?>
    </td>
    <td colspan="3">
      <?php echo $form['taxon_ref'];?>
    </td>
    <td>
    </td>
  </tr>
  <tr class="accompanying_unit" style="<?php echo ($form['accompanying_type']->getValue()=='biological')?'display: none;':''?>">
    <td class="left_tabed">
      <?php echo $form['mineral_ref']->renderLabel();?>
    </td>
    <td colspan="3">
      <?php echo $form['mineral_ref'];?>
    </td>
    <td>
    </td>
  </tr>
</tbody>
<script type="text/javascript">
  $(document).ready(function () {
    $("#clear_accompanying_unit_<?php echo $rownum;?>").click( function()
    {
      parent = $(this).closest('tbody');
      nvalue='';
      tvalue = '-';
      bvalue = 'Change !';
      $(parent).find('input[id$=\"_taxon_ref\"]').val(nvalue);
      $(parent).find('input[id$=\"_taxon_ref_name\"]').val(tvalue);
      $(parent).find('div[id$=\"_taxon_ref_button\"]').find('a').html(bvalue);
      $(parent).find('input[id$=\"_mineral_ref\"]').val(nvalue);
      $(parent).find('input[id$=\"_mineral_ref_name\"]').val(tvalue);
      $(parent).find('div[id$=\"_mineral_ref_button\"]').find('a').html(bvalue);
      $(parent).find('input[id$=\"_quantity\"]').val(nvalue);
      $(parent).hide();
      visibles = $(parent).closest('table.property_values').find('tbody:visible').size();
      if(!visibles)
      {
        $(this).closest('table.property_values').find('thead').hide();
      }
    });

    $('select[id$=\"SpecimensAccompanying_<?php echo $rownum;?>_accompanying_type\"]').change(function()
    {
      $(this).closest('tbody').find('tr.accompanying_unit').toggle();
    });
  });
</script>