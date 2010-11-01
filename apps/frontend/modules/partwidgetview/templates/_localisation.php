<table class="catalogue_table_view">
<?php if ($part->getBuilding() != '' && $part->getFloor() !='' && $part->getRoom()!='' && $part->getRow()!='' && $part->getShelf() != '') : ?>
  <tr>
	<th class="top_aligned"><?php echo __("Building");?></th>
	<td><?php echo $part->getBuilding()==''?'-':$part->getBuilding() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Floor");?></th>
	<td><?php echo $part->getFloor()==''?'-':$part->getFloor() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Room");?></th>
	<td><?php echo $part->getRoom()==''?'-':$part->getRoom() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Row");?></th>
	<td><?php echo $part->getRow()==''?'-':$part->getRow() ?></td>
  </tr>
  <tr>
	<th class="top_aligned"><?php echo __("Shelf");?></th>
	<td><?php echo $part->getShelf()==''?'-':$part->getShelf() ?></td>
  </tr>
<?php endif ; ?>
</table>
