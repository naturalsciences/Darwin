<?php slot('widget_title',__('Communication'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Communication Type');?></th>
      <th><?php echo __('Value');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($comms as $comm):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Edit Communication Mean');?>"  href="<?php echo url_for('people/comm?ref_id='.$eid.'&id='.$comm->getId());?>">
	      <?php echo $comm->getCommType();?>
      </a>
    </td>
    <td>
      <?php echo $comm->getEntry();?>
      <?php echo $comm->getTag();?>
    </td>

    <td class="widget_row_delete">
      <a class="widget_row_delete" href="<?php echo url_for('people/deleteComm?id='.$comm->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br />
<?php echo image_tag('add_green.png');?>
<a title="<?php echo __('Add Communication Mean');?>" class="link_catalogue" href="<?php echo url_for('people/comm?ref_id='.$eid);?>"> 
  <?php echo __('Add');?>
</a>