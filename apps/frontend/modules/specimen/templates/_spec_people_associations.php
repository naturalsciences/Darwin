        <tbody class="spec_ident_collectors_data" id="spec_ident_collectors_data_<?php echo $rownum;?>">
          <?php if($form->hasError()): ?>
            <tr>
              <td colspan="3">
                <?php echo $form->renderError();?>
              </td>
            </tr>
          <?php endif;?>
          <tr class="spec_ident_collectors_data">
            <td class="spec_ident_collectors_handle"><?php echo image_tag('drag.png');?></td>
            <td><?php echo $form['people_ref']->render();?></td>
            <td class="widget_row_delete">
              <?php echo image_tag('remove.png', 'alt=Delete class=clear_code id=clear_collector_'.$rownum); ?>
              <?php echo $form->renderHiddenFields();?>
            </td>
          </tr>
        </tbody>
	<script type="text/javascript">
	  $(document).ready(function () {
	    $("#clear_collector_<?php echo $rownum;?>").click( function()
	    {
	      parent = $(this).closest('tbody');
	      parentTableId = $(parent).closest('table').attr('id');
	      nvalue='';
	      tvalue='-';
	      bvalue='Choose !';
	      $(parent).find('input[id$=\"_people_ref\"]').val(nvalue);
	      $(parent).find('div[id$=\"_people_ref_name\"]').text(tvalue);
	      $(parent).find('div[id$=\"_people_ref_button\"]').find('a').text(bvalue);
	      $(parent).hide();
	      reOrderIdentifiers(parentTableId);
	      visibles = $('table#'+parentTableId+' tbody.spec_ident_collectors_data:visible').size();
	      if(!visibles)
	      {
		$(this).closest('table#'+parentTableId).find('thead').hide();
	      }
	    });
	  });
	</script>
