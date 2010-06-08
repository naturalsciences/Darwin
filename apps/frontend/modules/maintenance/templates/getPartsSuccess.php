<ul class="checkbox_list">
<?php foreach($parts as $part):?>
  <li>
	<input name="specimen_parts_filters[parts][]" type="checkbox" value="<?php echo $part->getId()?>" id="specimen_parts_filters_parts_<?php echo $part->getId()?>" />&nbsp;
	<label for="specimen_parts_filters_parts_<?php echo $part->getId()?>"><?php echo $part->getName();?></label>
  </li>
<?php endforeach;?>
</ul>