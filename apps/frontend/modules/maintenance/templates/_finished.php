<div class="page">
  <?php echo format_number_choice('[1]Maintenance added to %1% record|(1,+Inf]Maintenance added to %1% records', array('%1%' =>  $parts_numbers), $parts_numbers);?> <br />
  <?php echo link_to(__("Click here to add a new maintenance item"),'maintenance/index');?>
</div>