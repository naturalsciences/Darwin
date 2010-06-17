          <tr>
              <td colspan="3">
                <?php echo $form->renderError();?>
              </td>
          </tr>
          <tr class="spec_ident_collectors_data" id="collector_<?php echo $row_num; ?>">
            <td class="spec_ident_collectors_handle"><?php echo image_tag('drag.png');?></td>
            <td><?php echo $form['people_ref']->renderLabel();?></td>
            <td class="widget_row_delete">
              <?php echo image_tag('remove.png', 'alt=Delete class=clear_code id=clear_collector_'.$row_num); ?>
              <?php echo $form->renderHiddenFields();?>
            </td>
          </tr>

		<script type="text/javascript">
		  $(document).ready(function () {
		    $("#clear_collector_<?php echo $row_num;?>").click( function()
		    {
			 parent = $(this).closest('tr');
			 parentTableId = $(parent).closest('table').attr('id')
			 nvalue='';
			 $(parent).find('input[id$=\"_people_ref\"]').val(nvalue);
			 $(parent).hide();
			 reOrderCollectors(parentTableId);
                visibles = $('table#'+parentTableId+' .spec_ident_collectors_data:visible').size();
                if(!visibles)
                {
                	$(this).closest('table#'+parentTableId).find('thead').hide();
                }
		    });
		  });
		</script>
