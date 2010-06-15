<script  type="text/javascript">
function forceHelper(e,ui)
{
   $(".ui-state-highlight").html("<td colspan='6' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");
}

function forceIdentifiersHelper(e,ui)
{
   $(".ui-state-highlight").html("<td colspan='3' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");
}

function reOrderIdent()
{
  $('table#identifications').find('tbody.spec_ident_data:visible').each(function (index, item){
    $(item).find('tr.spec_ident_data input[id$=\"_order_by\"]').val(index+1);
  });
}

function reOrderIdentifiers(tableId)
{
  $('table#'+tableId).find('tbody.spec_ident_identifiers_data:visible').each(function (index, item){
    $(item).find('tr.spec_ident_identifiers_data input[id$=\"_order_by\"]').val(index+1);
  });
}
</script>
<table class="property_values" id="identifications">
  <thead style="<?php echo ($form['Identifications']->count() || $form['newIdentification']->count())?'':'display: none;';?>" class="spec_ident_head">
    <tr>
      <th>
        <?php echo $form['ident'];?>
      </th>
      <th>
        <?php echo __('Date'); ?>
      </th>
      <th>
        <?php echo __('Subject'); ?>
      </th>
      <th>
        <?php echo __('Value'); ?>
      </th>
      <th>
        <?php echo __('Det. St.'); ?>
      </th>
      <th>
      </th>
    </tr>
  </thead>
    <?php $retainedKey = 0;?>
    <?php foreach($form['Identifications'] as $form_value):?>
      <?php include_partial('specimen/spec_identifications', array('form' => $form_value, 'row_num'=>$retainedKey, 'module'=>$module, 'spec_id'=>$spec_id, 'individual_id'=>$individual_id));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
    <?php foreach($form['newIdentification'] as $form_value):?>
      <?php include_partial('specimen/spec_identifications', array('form' => $form_value, 'row_num'=>$retainedKey, 'module'=>$module, 'spec_id'=>$spec_id, 'individual_id'=>$individual_id));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
  <tfoot>
    <tr>
      <td colspan='6'>
        <div class="add_code">
          <a href="<?php echo url_for($module.'/addIdentification'. (($spec_id == 0) ? '': '?spec_id='.$spec_id.(($individual_id == 0) ? '': '&individual_id='.$individual_id)));?>/num/" id="add_identification"><?php echo __('Add identification');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
<script  type="text/javascript">

$(document).ready(function () {

    $('#add_identification').click(function()
    {
        parent = $(this).closest('table#identifications');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ ($('tbody.spec_ident_data').length) + '/order_by/' + ($('tbody.spec_ident_data:visible').length+1),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead.spec_ident_head:hidden').show();
          }
        });
        return false;
    });    

    $("#identifications").sortable({
         placeholder: 'ui-state-highlight',
         handle: '.spec_ident_handle',
         axis: 'y',
         change: function(e, ui) {
           forceHelper(e,ui);
         },
         deactivate: function(event, ui) {
           reOrderIdent();
         }
       });

    $('.spec_ident_identifiers_data input[id$=\"people_ref\"]').live('change', function()
    {
	ref_element_id = $(this).closest('table').attr('id') ;
	ref_element_value = $(this).attr('value');
	$cpt = 0 ;
	$('.spec_ident_identifiers_data input[id$=\"people_ref\"]').each(function() {
	    if($(this).closest('table').attr('id') == ref_element_id) 
	    {
		    if($(this).attr('value') == ref_element_value) $cpt++ ;
	    }
	});
	if($cpt > 1) $(this).closest('tr').find('.clear_identifier').trigger('click') });     
});
</script>
