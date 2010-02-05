<?php foreach($relations as $relation):?>
    <tr class="rid_<?php echo $relation->Parent->getId();?>">
    <td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;',$level);?><?php echo image_tag('info.png',"title=info class='info lid_".($level+1)."'");?></td>
    <td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;',$level);?><?php echo $relation->Child;?></td>
    <td><?php echo $relation->getRelationshipType();?></td>
    <td><?php echo $relation->Parent;?></td>
    <td><?php echo $relation->getPersonUserRole();?></td>
    <td class="datesNum">
	<?php echo $relation->getActivityDateFromObject()->getDateMasked('em','Y');?> - 
	<?php echo $relation->getActivityDateToObject()->getDateMasked('em','Y') ?>
    </td>
    <td class="widget_row_delete">
      <script language="javascript">
	$(".rid_<?php echo $relation->Parent->getId();?> > td > img.info").click(fetchRelDetails);
      </script>
    </td>
  </tr>
<?php endforeach;?>
