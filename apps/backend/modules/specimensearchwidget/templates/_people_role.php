<table class="full_size">
  <thead>
  <tr>
      <td colspan="2">
        <input type="button" id='people_switch_precise' value="<?php echo __('Precise search'); ?>" disabled>
        <input type="button" id='people_switch_fuzzy' value="<?php echo __('Fuzzy search'); ?>">
      </td>
    </tr>
    <tr >
      <th class="precise_people"><?php echo $form['people_ref']->renderLabel();?></th>
	  <th class="fuzzy_people hidden"><?php echo $form['people_fuzzy']->renderLabel();?></th>
      <th><?php echo $form['role_ref']->renderLabel();?></th>
    </tr>
  </thead>
  <tbody>
    <tr >
      <td class="precise_people"><?php echo $form['people_ref'];?></td>
	  <td class="fuzzy_people hidden"><?php echo $form['people_fuzzy'];?></td>
      <td><?php echo $form['role_ref'];?></td>
    </tr>
  </tbody>
</table>

<script type="text/javascript">
$(document).ready(function () {
  $('#people_switch_precise').click(function() {
    $('#people_switch_precise').attr('disabled','disabled') ;
    $('.fuzzy_people').removeAttr('disabled') ;
    $('.precise_people').toggle() ;
    $(this).closest('table').find('.people_switch_fuzzy').toggle() ;
    $('#specimen_search_filters_people_ref').val("") ;
	$('#specimen_search_filters_people_ref').val("") ;
    $('#specimen_search_filters_people_ref_name').html('') ;
	check_state();
  });

  $('#people_switch_fuzzy').click(function() {

    $('#people_switch_fuzzy').removeAttr('disabled') ;
    $('.fuzzy_people').attr('disabled','disabled') ;
    $('.precise_people').toggle() ;
    $(this).closest('table').find('.fuzzy_people').toggle() ;
    $('.fuzzy_people').find('input:text').val("") ;
	check_state();
  });
 
});

  function check_state()
  {
			if(($(".fuzzy_people").is(":visible")))
			{
				
				if($("#specimen_search_filters_people_ref_name").text().length>0)
				{
					$('.fuzzy_people').find('input:text').val($("#specimen_search_filters_people_ref_name").text())
					$("#specimen_search_filters_people_ref_name").text("");
					$("#specimen_search_filters_people_ref").val("");
				}
			}
			else if(($(".precise_people").is(":visible")))
			{

				$('.fuzzy_people').find('input:text').val("");
			}
  }
</script>