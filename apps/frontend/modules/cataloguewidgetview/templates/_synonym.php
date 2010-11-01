<table class="catalogue_table_view">
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
	<?php echo __(ucfirst($group_name));?>
      </td>
      <td>
	  <table class="grp_id_<?php echo $group[0]['group_id'];?> widget_sub_table" alt="<?php echo $group_name;?>">
	    <thead>
	      <tr>
		<th><?php echo __('Name');?></th>
		<th>
		  <?php if($group_name != "homonym" ):?>
		    <?php echo __('Basionym');?>
		  <?php endif;?>
		</th>
		<th></th>
	      </tr>
	    </thead>
	    <tbody >
	    <?php foreach($group as $synonym):?>
	      <tr class="syn_id_<?php echo $synonym['id'];?>" id="id_<?php echo $synonym['id'];?>">
		<td>
		  <?php if($synonym['record_id'] == $eid):?>
		      <strong><?php echo $synonym['ref_item']->getNameWithFormat();?></strong>
		  <?php else:?>
		    <?php echo $synonym['ref_item']->getNameWithFormat();?>
		  <?php endif;?>
		</td>
		<td class="basio_cell">
		  <?php if($group_name != "homonym"):?>
		    <a href="#" <?php if($synonym['is_basionym']):?> class="checked"<?php endif;?>></a>
		  <?php endif;?>
		</td>
	      </tr>
	    <?php endforeach;?>
	    </tbody>
	  </table>
      </td>

    </tr>
    <?php endforeach;?>
  </tbody>
</table>
