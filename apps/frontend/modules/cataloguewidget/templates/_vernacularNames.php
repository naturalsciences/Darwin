<?php slot('widget_title',__('Vernacular Names'));  ?>
<ul class="hidden error_list">
  <li></li>
</ul>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Community');?></th>
      <th><?php echo __('Names');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($vernacular_names as $vernacular_name):?>
    <tr>
      <td>
	  <a class="link_catalogue" title="<?php echo __('Edit Vernacular Names');?>" href="<?php echo url_for('vernacularnames/add?table='.$table.'&rid='.$vernacular_name->getId().'&id='.$eid); ?>">
	    <?php echo $vernacular_name->getCommunity();?>
	  </a>
      </td>
      <td>
	<?php if( count($vernacular_name->VernacularNames) ):?>
	  <a href="#" class="display_value"><?php echo format_number_choice('[1]Show 1 Name|(1,+Inf]Show %1% Names', array('%1%' => count($vernacular_name->VernacularNames) ), count($vernacular_name->VernacularNames));?></a>
	  <a href="#" class="hidden hide_value"><?php echo __('Hide Names');?></a>
	  <ul class="hidden">
	    <?php foreach($vernacular_name->VernacularNames as $name):?>
	      <li>
		<?php echo $name->getName();?>
	      </li>
	    <?php endforeach;?>
	  </ul>
	<?php else:?>
	  <?php echo __('No Names');?>
	<?php endif;?>
      </td>
      <td class="widget_row_delete">
        <a class="widget_row_delete" href="<?php echo url_for('vernacularnames/delete?id='.$vernacular_name->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
        </a>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

<script>
$('.display_value').click(function()
{
  $(this).parent().find('ul').slideDown();
  $(this).parent().find('.hide_value').show();
  $(this).hide();
  return false;
});
$('.hide_value').click(function()
{
  $(this).parent().find('ul').slideUp();
  $(this).parent().find('.display_value').show();
  $(this).hide();
  return false;
});
</script>
<br />
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Names');?>" class="link_catalogue" href="<?php echo url_for('vernacularnames/add?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>