<?php echo javascript_include_tag('collectors.js') ?>
<script  type="text/javascript">
function forceCollectorsHelper(e,ui)	
{
   $(".ui-state-highlight").html("<td colspan='3' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");	
}
function reOrderCollectors(tableId)	
{
  $('table#'+tableId).find('tbody.spec_ident_collectors_data tr:visible').each(function (index, item){
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
   <tbody id="spec_ident_collectors_body">
   <?php $retainedKey = 0;?>
   <?php foreach($form['Collectors'] as $form_value):?>
     <?php include_partial('specimen/spec_people_associations', array('form' => $form_value, 'row_num'=>$retainedKey));?>
     <?php $retainedKey = $retainedKey+1;?>
   <?php endforeach;?>
   <?php foreach($form['newCollectors'] as $form_value):?>
     <?php include_partial('specimen/spec_people_associations', array('form' => $form_value, 'row_num'=>$retainedKey));?>
     <?php $retainedKey = $retainedKey+1;?>
   <?php endforeach;?>
   </tbody>
   <tfoot>
     <tr>
       <td colspan="3">
         <div class="add_code">
           <a href="<?php echo url_for('specimen/AddCollector'.($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" class="hidden"></a>
           <a class='add_collector' href="<?php echo url_for('people/choose?only_role=16');?>"><?php echo __('Add collector');?></a>
         </div>
       </td>
     </tr>
   </tfoot>
 </table>
	
 
<script  type="text/javascript">
$(document).ready(function () {
   $("#spec_ident_collectors_body").sortable({
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
});
</script>
