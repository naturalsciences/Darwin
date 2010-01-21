<div class="edit_syn_screen">
<ul class="hidden error_list">
  <li></li>
</ul>

<table class="edition edit_synon">
  <thead>
    <tr>
      <th></th>
      <th>Item</th>
     <?php if(! isset($groups["homonym"])):?>
      <th>Basionym</th>
     <?php endif;?>
      <th></th>
    </tr>
  </thead>

  <tbody>
  <?php foreach($groups as $group => $synonyms):?>
    <?php foreach($synonyms as $synonym):?>
      <tr class="syn_id_<?php echo $synonym['id'];?>" id="id_<?php echo $synonym['id'];?>">
	<td class="handle"><?php echo image_tag('drag.png');?></td>
	<td><?php echo $synonym['ref_item']->getNameWithFormat();?></td>

      <?php if($group != "homonym"):?>
	<td><input type="checkbox" name="basionym" class="basionym_checkbox" value="<?php echo $synonym['id'];?>" <?php if($synonym['is_basionym']) echo 'checked="checked"';?>></td>
      <?php endif;?>
	<td class="widget_row_delete">
	  <?php if($synonym['record_id'] == $sf_request->getParameter('id')):?>
	    <a class="widget_row_delete" href="<?php echo url_for('synonym/delete?id='.$synonym['id']);?>" title="<?php echo __('Are you sure ?') ?>">
	      <?php echo image_tag('remove.png'); ?>
	    </a>
	  <?php endif;?>
	</td>
      </tr>
    <?php endforeach;?>
  <?php endforeach;?>
  </tbody>
    <tfoot>
      <tr>
        <td colspan="3">
	  <form id="edit_syn_form"  action="<?php echo url_for('synonym/edit?table='.$sf_request->getParameter('table').'&id='.$sf_request->getParameter('id') .'&group_id='.$sf_request->getParameter('group_id') );?>" method="post">
	    <?php echo $form;?>
	    <a href="#" class="cancel_qtip">Cancel</a>
	    <input id="save" class="save" type="submit" name="submit" value="<?php echo __('Save');?>" />
	  </form>
        </td>
      </tr>
    </tfoot>
</table>

<script type="text/javascript">

function forceHelper(e,ui)
{
   $(".ui-state-highlight").html("<td colspan='3'>&nbsp;</td>");
}

$(document).ready(function()
{
    function addError(html)
    {
      $('.error_list li').text(html);
      $('.error_list').show();
    }
    function removeError()
    {
	$('.error_list').hide();
	$('.error_list li').text(' ');
    }

  $(".edit_synon tbody").sortable({
      placeholder: 'ui-state-highlight',
      handle: '.handle',
      axis: 'y',
      change: function(e, ui) {
	forceHelper(e,ui);
      }
  });
    
  $(":checkbox").click(function()
  {
    if($(this).is(':checked'))
    {
      $(":checked").removeAttr("checked");
      $(this).attr('checked','checked');
    }
    else
    {
      $(":checked").removeAttr("checked");
    }
  });
  
  $("#edit_syn_form").submit(function()
  {
	removeError();
	el_Array = $(".edit_synon tbody").sortable('toArray');
	for(item in el_Array)
	{
	  $('#synonym_edit_orders').val( $('#synonym_edit_orders').val() + ',' + getIdInClasses( $('#'+el_Array[item]) ) );
	}

	$('#synonym_edit_basionym_id').val(' ');
	$('#synonym_edit_basionym_id').val( $('.basionym_checkbox:checked').val() );
	$.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == "ok" )
	    {
	     $('.qtip-button').click();
	    }
	    else
	    {
	     addError(html);
	    }
	  },
	  error: function(xhr)
	  {
	    addError('Error!  Status = ' + xhr.status);
	  }});
	  return false;
 });

});
</script>
</div>
