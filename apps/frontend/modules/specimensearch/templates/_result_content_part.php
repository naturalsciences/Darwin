<td class="col_part"><?php echo $specimen->getPart();?></td> 
<td class="col_part_status"><?php echo $specimen->getPartStatus();?></td> 
<td class="col_building"><?php echo $specimen->getBuilding();?></td> 
<td class="col_floor"><?php echo $specimen->getFloor();?></td> 
<td class="col_room"><?php echo $specimen->getRoom();?></td> 
<td class="col_row"><?php echo $specimen->getRow();?></td> 
<td class="col_shelf"><?php echo $specimen->getShelf();?></td> 
<td class="col_container"><?php echo $specimen->getContainer();?></td> 
<td class="col_container_type"><?php echo $specimen->getContainerType();?></td> 
<td class="col_container_storage"><?php echo $specimen->getContainerStorage();?></td> 
<td class="col_sub_container"><?php echo $specimen->getSubContainer();?></td> 
<td class="col_sub_container_type"><?php echo $specimen->getSubContainerType();?></td> 
<td class="col_sub_container_storage"><?php echo $specimen->getSubContainerStorage();?></td> 
<td class="col_part_count">
  <?php if($specimen->getPartCountMin() != $specimen->getPartCountMax()):?>
    <?php echo $specimen->getPartCountMin() . ' - '.$specimen->getPartCountMax();?>
  <?php else:?>
    <?php echo $specimen->getPartCountMin();?>
  <?php endif;?>
</td> 