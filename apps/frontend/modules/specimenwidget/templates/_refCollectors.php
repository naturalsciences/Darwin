<?php $read_only = (isset($view)&&$view)?true:false ; ?>
<?php echo javascript_include_tag('catalogue_people.js') ?>
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
       <?php if (!$read_only) : ?>     
         <?php include_partial('specimen/spec_people_associations', array('form' => $form_value, 'row_num'=>$retainedKey));?>
         <?php $retainedKey = $retainedKey+1;?>
       <?php else : ?>
        <tr><td>
          <a href="<?php echo url_for('people/view?id='.$form_value['people_ref']->getvalue()) ; ?>"><?php echo $form_value['people_ref']->renderLabel() ; ?></a>
        </td></tr>
       <?php endif ; ?>
     <?php endforeach;?>
     <?php foreach($form['newCollectors'] as $form_value):?>
       <?php include_partial('specimen/spec_people_associations', array('form' => $form_value, 'row_num'=>$retainedKey));?>
       <?php $retainedKey = $retainedKey+1;?>
     <?php endforeach;?>
   </tbody>
   <?php if (!$read_only) : ?>       
   <tfoot>
     <tr>
       <td colspan="3">
         <div class="add_code">
           <a href="<?php echo url_for('specimen/AddCollector'.($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" class="hidden"></a>
           <a class='add_collector' href="<?php echo url_for('people/choose?only_role=16&with_js=1');?>"><?php echo __('Add collector');?></a>
         </div>
       </td>
     </tr>
   </tfoot>
 </table>
	

<script  type="text/javascript">
$(document).ready(function () {


function addCollector(people_ref, people_name)
{ 
  info = 'ok';
  $('#spec_ident_collectors tbody tr').each(function() {
    if($(this).find('input[id$=\"_people_ref\"]').val() == people_ref) info = 'bad' ;
  });
  if(info != 'ok') return false;


  $.ajax(
  {
    type: "GET",
    url: $('#spec_ident_collectors .add_code a.hidden').attr('href')+ (0+$('#spec_ident_collectors tbody tr').length)+'/people_ref/'+people_ref + '/iorder_by/' + (0+$('#spec_ident_collectors tbody tr').length),
    success: function(html)
    {
      $('#spec_ident_collectors tbody').append(html);
      $.fn.catalogue_people.reorder($('#spec_ident_collectors'));
    }
  });
  return true;
}

$("#spec_ident_collectors").catalogue_people({ add_button: '#spec_ident_collectors a.add_collector', q_tip_text: 'Choose a Collector',update_row_fct: addCollector });


});

</script>
<?php else : ?> 
</table>
<?php endif ; ?>
