<table class="catalogue_table_view">
  <tr>
	<th><?php echo __("Surnumerary ? ");?></th>
	<td><?php echo ($part->getSurnumerary()?__('Yes'):__('No')); ?></td>
  </tr>

  <tr>
	<th><?php echo __("Container");?></th>
	<td><?php echo $part->getContainer() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Container type");?></th>
	<td><?php echo $part->getContainerType() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Container storage");?></th>
	<td><?php echo $part->getContainerStorage() ?></td>
  </tr>
  <tr>
	<th><?php echo __("Sub container");?></th>
	<td><?php echo $part->getSubContainer() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Sub container type");?></th>
	<td><?php echo $part->getSubContainerType() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Sub container storage");?></th>
	<td><?php echo $part->getSubContainerStorage() ?></td>
  </tr>
</table>
