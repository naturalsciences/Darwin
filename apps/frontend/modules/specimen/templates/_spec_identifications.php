<tbody class="spec_ident_data" id="spec_ident_data_<?php echo $row_num;?>">
  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="6">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr class="spec_ident_data">
    <td class="spec_ident_handle">
      <?php echo image_tag('drag.png');?>
    </td>
    <td>
      <?php echo $form['notion_date'];?>
    </td>
    <td>
      <?php echo $form['notion_concerned'];?>
    </td>
    <td>
      <?php echo $form['value_defined'];?>
    </td>
    <td>
      <?php echo $form['determination_status'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_identification id=clear_identification_'.$row_num); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
  <tr class="spec_ident_identifiers">
    <td></td>
    <td colspan="4">
      <?php $borderClass = (!$form['Identifiers']->count() && !$form['newIdentifier']->count())?'':'green_border';?>
      <table class="property_values identifiers <?php echo $borderClass;?>" id="spec_ident_identifiers_<?php echo $row_num;?>">
        <thead style="<?php echo ($form['Identifiers']->count() || $form['newIdentifier']->count())?'':'display: none;';?>" class="spec_ident_identifiers_head">
          <tr>
            <td colspan="3"><?php echo __('Identifiers');?></td>
          </tr>
        </thead>
	<?php $retainedKey = 0;?>
        <?php foreach($form['Identifiers'] as $form_value):?>
          <?php include_partial('specimen/spec_identification_identifiers', array('form' => $form_value, 'rownum'=>$retainedKey));?>
	  <?php $retainedKey = $retainedKey+1;?>
        <?php endforeach;?>
        <?php foreach($form['newIdentifier'] as $form_value):?>
          <?php include_partial('specimen/spec_identification_identifiers', array('form' => $form_value, 'rownum'=>$retainedKey));?>
	  <?php $retainedKey = $retainedKey+1;?>
        <?php endforeach;?>
        <tfoot>
          <tr>
            <td colspan="3">
              <div class="add_code">
                <a href="<?php echo url_for($module.'/addIdentifier'.(($spec_id == 0) ? '': '?spec_id='.$spec_id.(($individual_id == 0) ? '': '&individual_id='.$individual_id))).'/num/'.$row_num.((!isset($form['id']) || (isset($form['id']) && $form['id']->getValue() == ''))?'':'/identification_id/'.$form['id']->getValue());?>/identifier_num/" class="add_identifier" id="add_identifier_<?php echo $row_num;?>"><?php echo __('Add identifier');?></a>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </td>
    <td></td>
  </tr>
</tbody>
<script  type="text/javascript">
  $(document).ready(function () {
    $("#clear_identification_<?php echo $row_num;?>").click( function()
    {
      parent = $(this).closest('tbody');
      nvalue='';
      $(parent).find('input[id$=\"_value_defined\"]').val(nvalue);
      $(parent).hide();
      reOrderIdent();
      visibles = $('table#identifications tbody.spec_ident_data:visible').size();
      if(!visibles)
      {
        $(this).closest('table#identifications').find('thead.spec_ident_head').hide();
      }
    });

    $("#add_identifier_<?php echo $row_num;?>").click( function()
    {
        parent = $(this).closest('table.identifiers');
        parentId = $(parent).attr('id');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ ($('table#'+parentId+' tbody.spec_ident_identifiers_data').length) + '/iorder_by/' + ($('table#'+parentId+' tbody.spec_ident_identifiers_data:visible').length+1),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead:hidden').show();
            $('table#'+parentId).toggleClass('green_border',true);
          }
        });
        return false;
    });

  $("#spec_ident_identifiers_<?php echo $row_num; ?>").sortable({
    placeholder: 'ui-state-highlight',
    handle: '.spec_ident_identifiers_handle',
    axis: 'y',
    change: function(e, ui) {
              forceIdentifiersHelper(e,ui);
            },
    deactivate: function(event, ui) {
                  reOrderIdentifiers($(this).attr('id'));
                }
    });
  });
</script>
