<?php slot('widget_title',__('Addresses'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Address');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($addresses as $address):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Edit Address');?>"  href="<?php echo url_for('people/address?ref_id='.$eid.'&id='.$address->getId());?>">
	<?php echo $address->getEntry();?>
      </a>
      <?php echo $address->getPoBox();?>
      <?php echo $address->getTag();?>
      <?php echo $address->getExtendedAddress();?>
      <?php echo $address->getLocality();?>
      <?php echo $address->getRegion();?>
      <?php echo $address->getZipCode();?>
      <?php echo $address->getCountry();?>
    </td>

    <td class="widget_row_delete">
      <a class="widget_row_delete" href="<?php echo url_for('people/deleteAddress?id='.$address->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br />
<?php echo image_tag('add_green.png');?>
<a title="<?php echo __('Add Address');?>" class="link_catalogue" href="<?php echo url_for('people/address?ref_id='.$eid);?>"> 
  <?php echo __('Add');?>
</a>