<?php slot('widget_title',__('Synonyms'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Type');?></th>
      <th><?php echo __('Items');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($synonyms as $group_name => $group):?>
    <tr>
      <td>
	  <a class="link_catalogue" title="<?php echo __('Edit Synonymies');?>" href="<?php echo url_for('synonym/edit?table='.$table.'&group_id='.$group[0]['group_id'].'&id='.$eid); ?>">
	    <?php echo $group_name;?>
	  </a>
      </td>
      <td>
	  <table>
	    <?php foreach($group as $synonym):?>
	      <tr>
		<td><?php echo $synonym['order_by'];?> - </td>
		<td>
		  <?php if($synonym['record_id'] == $eid):?>
		      <strong><?php echo $synonym['name'];?></strong>
		  <?php else:?>
		    <?php echo $synonym['name'];?>
		  <?php endif;?>
		  
		  <?php if($synonym['is_basionym']):?>
		    <em>( <?php echo __('Basionym');?> )</em>
		  <?php endif;?>
		</td>
		<td class="widget_row_delete">
		  <?php if($synonym['record_id'] == $eid):?>
		    <a class="widget_row_delete" href="<?php echo url_for('synonym/delete?id='.$synonym['id']);?>" title="<?php echo __('Are you sure ?') ?>">
		     <?php echo image_tag('remove.png'); ?>
		    </a>
		  <?php endif;?>
		</td>
	      </tr>
	    <?php endforeach;?>
	  </table>
      </td>

    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<br />
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Synonymies');?>" class="link_catalogue" href="<?php echo url_for('synonym/index?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>