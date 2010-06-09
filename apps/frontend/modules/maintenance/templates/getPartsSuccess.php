<ul class="checkbox_list">
<?php foreach($parts as $part):?>
  <li>
	<input name="specimen_parts_filters[parts][]" type="checkbox" value="<?php echo $part->getId()?>" id="specimen_parts_filters_parts_<?php echo $part->getId()?>" />&nbsp;
	<label for="specimen_parts_filters_parts_<?php echo $part->getId()?>">	ID: <?php echo $part->getName();?> 
	<?php $nbr = (isset($maintenances[$part->getId()]) ?  $maintenances[$part->getId()] : 0) ;?>
	<sup>(<?php echo format_number_choice('[0] 0 Maintenances|[1] 1 Maintenance |(1,+Inf] %1% Maintenances', array('%1%' =>  $nbr), $nbr);?>)</sup></label>
  </li>
<?php endforeach;?>
</ul>