<?php slot('widget_title',__('Relations'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Institution');?></th>
      <th><?php echo __('Relation');?></th>
      <th><?php echo __('Role');?></th>
      <th class="datesNum"><?php echo __('Period');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($relations as $relation):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Edit Relation');?>"  href="<?php echo url_for('people/relation?ref_id='.$eid.'&id='.$relation->getId());?>">
      <?php echo $relation->Parent->getFamilyName();?>
      </a>
    </td>
    <td><?php echo $relation->getRelationshipType();?></td>
    <td><?php echo $relation->getPersonUserRole();?></td>
    <td class="datesNum">
	<?php echo $relation->getActivityDateFromObject()->getDateMasked('em','Y');?> - 
	<?php echo $relation->getActivityDateToObject()->getDateMasked('em','Y') ?>
    </td>
    <td class="widget_row_delete">
      <a class="widget_row_delete" href="<?php echo url_for('people/deleteRelation?id='.$relation->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br />
<?php echo image_tag('add_green.png');?>
<a title="<?php echo __('Add Relationship');?>" class="link_catalogue" href="<?php echo url_for('people/relation?ref_id='.$eid);?>"><?php echo __('Add');?></a>