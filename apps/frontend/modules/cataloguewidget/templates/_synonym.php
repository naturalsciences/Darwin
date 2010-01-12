<?php slot('widget_title',__('Synonyms'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Type');?></th>
      <th><?php echo __('Items');?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($synonyms as $synonym):?>
    <tr>
      <td>
	  <a class="link_catalogue" title="<?php echo __('Edit Synonymies');?>" href="<?php echo url_for('synonym/index?table='.$table.'&rid='.$synonym->getId().'&id='.$eid); ?>">
	    <?php echo $synonym->getGroupName();?>
	  </a>
      </td>
      <td>
	<?php  if(false): // count($synonym->PropertiesValues) ):?>
	  <a href="#" class="display_value">...</a>
	  <a href="#" class="hidden hide_value">++++</a>
	  <ul class="hidden">
	    <?php //foreach($synonym->PropertiesValues as $value):?>
	      <li>
		<?php //echo $value->getPropertyValue();?> <?php //echo $synonym->getPropertyUnit();?> 
	      </li>
	    <?php //endforeach;?>
	  </ul>
	<?php else:?>
	  <?php echo __('No Values');?>
	<?php endif;?>
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
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Synonymies');?>" class="link_catalogue" href="<?php echo url_for('synonym/index?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>