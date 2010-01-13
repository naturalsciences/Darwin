<?php slot('widget_title',__('Synonyms'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Type');?></th>
      <th><?php echo __('Items');?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($synonyms as $group_name => $group):?>
    <tr>
      <td>
	  <a class="link_catalogue" title="<?php echo __('Edit Synonymies');?>" href="<?php echo url_for('synonym/index?table='.$table.'&rid='.'&id='.$eid); ?>">
	    <?php echo $group_name;?>
	  </a>
      </td>
      <td>
	  <ul>
	    <?php foreach($group as $synonym):?>
	      <li>
		<?php if($synonym['is_basionym']):?>
		    <em>B</em>
		<?php endif;?>
		<?php echo $synonym['name'];?> [<?php echo $synonym['order_by'];?>]
	      </li>
	    <?php endforeach;?>
	  </ul>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<br />
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Synonymies');?>" class="link_catalogue" href="<?php echo url_for('synonym/index?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>