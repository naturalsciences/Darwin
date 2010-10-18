<table class="catalogue_table<?php echo($level == Users::REGISTERED_USER?'_view':'') ;?>">
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
      <?php if($level > Users::REGISTERED_USER) : ?>      
	  <a class="link_catalogue" title="<?php echo __('Edit Vernacular Names');?>" href="<?php echo url_for('vernacularnames/add?table='.$table.'&rid='.$vernacular_name->getId().'&id='.$eid); ?>">
	    <?php echo $vernacular_name->getCommunity();?>
	  </a>
	     <?php else : ?>
	      <?php echo $vernacular_name->getCommunity();?>
	     <?php endif ; ?>
      </td>
      <td>
	<?php if( count($vernacular_name->VernacularNames) > 1):?>
	  <a href="#" class="display_value"><?php echo format_number_choice('[1]Show 1 Name|(1,+Inf]Show %1% Names', array('%1%' => count($vernacular_name->VernacularNames) ), count($vernacular_name->VernacularNames));?></a>
	  <a href="#" class="hidden hide_value"><?php echo __('Hide Names');?></a>
	  <ul class="hidden">
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
      <td class="widget_row_delete">
      <?php if($level > Users::REGISTERED_USER) : ?>       
        <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=class_vernacular_names&id='.$vernacular_name->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
        </a>
      <?php endif ; ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

<script type="text/javascript">
$('.display_value').click(showValues);
$('.hide_value').click(hideValues);
</script>
<br />
<?php if($level > Users::REGISTERED_USER) : ?>
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Names');?>" class="link_catalogue" href="<?php echo url_for('vernacularnames/add?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>
<?php endif ; ?>
