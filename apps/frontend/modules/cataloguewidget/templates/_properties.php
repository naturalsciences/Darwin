<?php slot('widget_title',__('Properties'));  ?>
<table>
  <?php foreach($properties as $property):?>
  <tr>
    <th><?php echo $property->getPropertyType();?></th>
    <td>
      <a class="link_catalogue" title="<?php echo __('Edit Properties');?>" href="<?php echo url_for('property/add?table='.$table.'&rid='.$property->getId().'&id='.$eid); ?>"><?php echo $property->getPropertyQualifier();?></a>
      <ul>
	<?php foreach($property->PropertiesValues as $value):?>
	  <li>
	    <?php echo $value->getPropertyValue();?> <?php echo $property->getPropertyUnit();?> 
	    <?php if($value->getPropertyAccuracy() != ""):?>
	      ( +- <?php echo $value->getPropertyAccuracy();?> )
	    <?php endif;?>
	  </li>
	<?php endforeach;?>    
      </ul>
    </td>
  </tr>
  <?php endforeach;?>
</table>

<br />
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Properties');?>" class="link_catalogue" href="<?php echo url_for('property/add?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>