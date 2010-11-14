<table class="catalogue_table_view">
  <thead>
    <tr>
      <th><?php echo __('Community');?></th>
      <th><?php echo __('Names');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody id="property">
    <?php foreach($vernacular_names as $vernacular_name):?>
    <tr>
      <td>
	      <?php echo $vernacular_name->getCommunity();?>
      </td>
      <td>
	<?php if( count($vernacular_name->VernacularNames) > 1):?>
	  <ul>
	    <?php foreach($vernacular_name->VernacularNames as $name):?>
	      <li>
		<?php echo $name->getName();?>
	      </li>
	    <?php endforeach;?>
	  </ul>
	<?php elseif( count($vernacular_name->VernacularNames) == 1):?>
	    <ul><li><?php echo $vernacular_name->VernacularNames[0]->getName();?></li></ul>
	<?php else:?>
	  <?php echo __('No Names');?>
	<?php endif;?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

