<?php slot('title', __('Edit Parts'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual, 'part' => new SpecimenParts(),'form'=> $form) );?>

<form method="post" action="<?php echo url_for('parts/edit?id='.$individual->getId());?>">

<table class="parts_grouped">

  <?php foreach($form['SpecimenParts'] as $num => $subform):?>
	<?php include_partial('partform',array('form'=> $subform));?>
  <?php endforeach;?>

  <?php foreach($form['newVal'] as $num => $subform):?>
	<?php include_partial('partform',array('form'=> $subform));?>
  <?php endforeach;?>

  <tfoot>
	<tr>
	  <td colspan="7">
		<a href="<?php echo url_for('parts/addNew?id='.$individual->getId());?>/num/" id="add_value"><?php echo __('Add New');?></a>
	  </td>
	</tr>

    <tr>
      <td colspan="7">
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>

<script  type="text/javascript">
$(document).ready(function () {
	/*$('#catalogue_properties_property_type').change(function() {
	  $.get("<?php echo url_for('property/getUnit');?>/type/"+$(this).val(), function (data) {
		$("#catalogue_properties_property_unit_parent select").html(data);
	  });
	});*/

    $('.clear_prop').live('click', function()
	{
	  parent = $(this).closest('tbody');
	  $(parent).find('input').val('');
	  $(parent).find('select option').removeAttr('selected');
	  $(parent).find("select[id$='_category']").html('');
	  $(parent).hide();
	});

    $('#add_value').click(function(){
	  $.ajax({
		type: "GET",
		url: $(this).attr('href')+ (0+$('.parts_grouped tbody').length),
		success: function(html)
		{
		  $('.parts_grouped').append(html);
		}
	  });
	  return false;
	});
});
</script>

</form>


<?php include_partial('specimen/specAfterTab');?>
