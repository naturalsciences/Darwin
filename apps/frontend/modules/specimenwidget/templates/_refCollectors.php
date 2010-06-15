<script  type="text/javascript">
function forceCollectorsHelper(e,ui)
{
   $(".ui-state-highlight").html("<td colspan='3' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");
}

function reOrderCollectors(tableId)
{
  $('table#'+tableId).find('tbody.spec_ident_collectors_data:visible').each(function (index, item){
    $(item).find('tr.spec_ident_collectors_data input[id$=\"_order_by\"]').val(index+1);
  });
}
</script>
 <table class="property_values collectors" id="spec_ident_collectors">
   <thead style="<?php echo ($form['Collectors']->count() || $form['newCollectors']->count())?'':'display: none;';?>" class="spec_ident_collectors_head">
	<tr>
	   <th colspan='3'>
		   <?php echo $form['collector'];?>
	   </th>
	</tr>
   </thead>
   <?php $retainedKey = 0;?>
   <?php foreach($form['Collectors'] as $form_value):?>
     <?php include_partial('spec_people_associations', array('form' => $form_value, 'rownum'=>$retainedKey));?>
     <?php $retainedKey = $retainedKey+1;?>
   <?php endforeach;?>
   <?php foreach($form['newCollectors'] as $form_value):?>
     <?php include_partial('spec_people_associations', array('form' => $form_value, 'rownum'=>$retainedKey));?>
     <?php $retainedKey = $retainedKey+1;?>
   <?php endforeach;?>
   <tfoot>
     <tr>
       <td colspan="3">
         <div class="add_code">
           <a href="<?php echo url_for('specimen/addCollector'.($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_collector"><?php echo __('Add collector');?></a>
         </div>
       </td>
     </tr>
   </tfoot>
 </table>
 
<script  type="text/javascript">
$(document).ready(function () {
   $("#spec_ident_collectors").sortable({
     placeholder: 'ui-state-highlight',
     handle: '.spec_ident_collectors_handle',
     axis: 'y',
     change: function(e, ui) {
              forceCollectorsHelper(e,ui);
            },
     deactivate: function(event, ui) {
                  reOrderCollectors($(this).attr('id'));
                }
    });
    $('#add_collector').click( function()
    {
        parent = $(this).closest('table.collectors');
        parentId = $(parent).attr('id');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ ($('table#'+parentId+' tbody.spec_ident_collectors_data').length) + '/iorder_by/' + ($('table#'+parentId+' tbody.spec_ident_collectors_data:visible').length+1),
          success: function(html)
          {                    
            $(parent).append(html);
          }
        });
        return false;
    });
    
    $('.spec_ident_collectors_data input[id$=\"people_ref\"]').live('change', function()
    {
	el = $(this).closest('tbody');
	ref_element_id = $(this).attr('value');
	$cpt = 0 ;
	$('.spec_ident_collectors_data input[id$=\"people_ref\"]').each(function() {
	    if($(this).attr('value') == ref_element_id) $cpt++ ;
	});
	if($cpt > 1) $(this).closest('tr').find('.clear_collectors').trigger('click') }); 
});
</script>
