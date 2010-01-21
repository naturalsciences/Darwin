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
	  <a class="link_catalogue" title="<?php echo __('Edit Synonyms');?>" href="<?php echo url_for('synonym/edit?table='.$table.'&group_id='.$group[0]['group_id'].'&id='.$eid); ?>">
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
		      <strong><?php echo $synonym['ref_item']->getNameWithFormat();?></strong>
		  <?php else:?>
		    <?php echo $synonym['ref_item']->getNameWithFormat();?>
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
<?php if($addAllowed):?><?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Synonymies');?>" class="link_catalogue" href="<?php echo url_for('synonym/add?table='.$table.'&id='.$eid);?>"><?php else:?><?php echo image_tag('add_grey.png');?><span class='add_not_allowed'><?php endif;?><?php echo __('Add');?><?php if($addAllowed):?></a><?php else:?></span><?php endif;?>
