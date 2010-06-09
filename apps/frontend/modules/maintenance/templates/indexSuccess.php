<?php slot('title', __('Maintenances'));  ?>
<div class="page" id="maintenance">
  <h1><?php echo __('Maintenances :');?></h1>
  <form name="maintenance" class="results maintenace_form">
  <h2><?php echo __('1. Search Parts : ');?></h2>
  <table id="maintenance_table" >
	<tr>
	  <th><?php echo $search_form['building']->renderLabel();?></th>
	  <td><?php echo $search_form['building'];?></td>
	</tr>

	<tr>
	  <th><?php echo $search_form['floor']->renderLabel();?></th>
	  <td><?php echo $search_form['floor'];?></td>
	</tr>

	<tr>
	  <th><?php echo $search_form['room']->renderLabel();?></th>
	  <td><?php echo $search_form['room'];?></td>
	</tr>

	<tr>
	  <th><?php echo $search_form['row']->renderLabel();?></th>
	  <td><?php echo $search_form['row'];?></td>
	</tr>

	<tr>
	  <th><?php echo $search_form['shelf']->renderLabel();?></th>
	  <td><?php echo $search_form['shelf'];?></td>
	</tr>
  
	<tr>
	  <th><?php echo $search_form['parts']->renderLabel();?></th>
	  <td class="parts_list"> <?php echo $search_form['parts'];?> </td>
	</tr>
	<tr>
	  <td></td><td><a class="add_to_list"><?php echo __('Add a part to the Queue');?></a></td>
	</tr>

  </table>

	  <div class="parts_count warn_message">
         <?php echo __('Maintenance will be applied to <span>0</span> parts');?> <a href="" id="clear_cnt"><?php echo __('Clear');?></a>
	  </div>  

  </form>
 
  <div class="action_maintenance">
	<?php include_partial('form', array('form' => $form) );?>
  </div>
  
</div>


<script  type="text/javascript">

$(document).ready(function () {

  $('form#collection_maintenance').submit(function () {
      $('form#collection_maintenance input[type=submit]').attr('disabled','disabled');
      //hideForRefresh($('form#collection_maintenance').parent());
      $.ajax({
          type: "POST",
          url: $(this).attr('action'),
          data: $(this).serialize(),
          success: function(html){
            $('form#collection_maintenance').before(html).remove();
          }
      });
      return false;
    });

 
  parent = $('#maintenance_table');
  $('.action_maintenance table').hide();

  $('#collection_maintenance_parts_ids').change(function()
  {
	if($(this).val() != '')
	  $('.action_maintenance table').show();
	else
	    $('.action_maintenance table').hide();
  });

  $('#maintenance_table .add_to_list').click(function(e){
	var values = Array();

	if($('#collection_maintenance_parts_ids').val()!= "")
	  values = $('#collection_maintenance_parts_ids').val().split(',');
	e.preventDefault();

	$('.checkbox_list input:checked').each(function (i,elem){
	  for (key in values)
	  {
		if(values[key] === $(elem).val())
		  return;
	  }
	  values.push( $(elem).val() );
	});
	$('#collection_maintenance_parts_ids').val(values.join(','));
	$('#collection_maintenance_parts_ids').trigger('change');
	$('.checkbox_list input').removeAttr('checked');
	$('.parts_count span').html( values.length );
	//$('#maintenace_form .parts_count').animate( { backgroundColor: '#E9F2F7' }, 1000).animate( { backgroundColor: 'white' }, 1000)

  });

  $("#clear_cnt").click(function (e){
	e.preventDefault();
	$('#collection_maintenance_parts_ids').val('');
	$('#collection_maintenance_parts_ids').trigger('change');
	$('.parts_count span').html(0);
  });

  $('#specimen_parts_filters_building').change(function(){
      $.get("<?php echo url_for('maintenance/getOptions');?>/field/floor/building/"+$(this).val(), function (data) {
              parent.find('select[name$="[floor]"]').html(data);
              parent.find('select[name$="[room]"]').html('');
              parent.find('select[name$="[row]"]').html('');
              parent.find('select[name$="[shelf]"]').html('');
            });
  });

  $('#specimen_parts_filters_floor').change(function(){
	  building = parent.find('select[name$="[building]"]').val();
      $.get("<?php echo url_for('maintenance/getOptions');?>/field/room/floor/"+$(this).val()+ "/building/" + building, function (data) {
              parent.find('select[name$="[room]"]').html(data);
              parent.find('select[name$="[row]"]').html('');
              parent.find('select[name$="[shelf]"]').html('');
            });
  });

  $('#specimen_parts_filters_room').change(function(){
	  building = parent.find('select[name$="[building]"]').val();
	  floor = parent.find('select[name$="[floor]"]').val();
      $.get("<?php echo url_for('maintenance/getOptions');?>/field/row/room/"+$(this).val()+ "/floor/" + floor + "/building/" + building, function (data) {
              parent.find('select[name$="[row]"]').html(data);
              parent.find('select[name$="[shelf]"]').html('');
            });
  });

  $('#specimen_parts_filters_row').change(function(){
	  building = parent.find('select[name$="[building]"]').val();
	  floor = parent.find('select[name$="[floor]"]').val();
	  room = parent.find('select[name$="[room]"]').val();
      $.get("<?php echo url_for('maintenance/getOptions');?>/field/shelf/row/"+$(this).val()+ "/room/"+ room + "/floor/" + floor + "/building/" + building, function (data) {
              parent.find('select[name$="[shelf]"]').html(data);
            });
  });

  $('#specimen_parts_filters_shelf').change(function(){
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('maintenance/getParts');?>",
	  data: $('form[name="maintenance"]').serialize(),
	  success: function(html){
		$(".parts_list").html(html);
	  }
	});
	return false;
  });  
});
</script>