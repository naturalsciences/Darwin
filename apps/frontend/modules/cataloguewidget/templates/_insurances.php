<table class="catalogue_table<?php echo($sf_user->isA(Users::REGISTERED_USER)?'_view':'') ;?>">
  <thead>
    <tr>
      <th><?php echo __('Value');?></th>
      <th><?php echo __('Year of reference');?></th>
      <th><?php echo __('Insurer');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($insurances as $insurance):?>
    <tr>
      <td>
      <?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>      
	  <a class="link_catalogue" title="<?php echo __('Edit (insurances) Values');?>" href="<?php echo url_for('insurances/add?table='.$table.'&rid='.$insurance->getId().'&id='.$eid); ?>">
	    <?php echo $insurance->getFormatedInsuranceValue();?>
	  </a>
	    <?php else : ?>
  	    <?php echo $insurance->getFormatedInsuranceValue();?>
	    <?php endif ; ?>
      </td>
      <td><?php echo $insurance->getFormatedInsuranceYear();?></td>
      <td>
        <?php if($insurance->People): ?>
          <?php echo $insurance->People->getFamilyName();?>
        <?php else: ?>
          <?php echo '-'; ?>
        <?php endif; ?>
      </td>
      <td class="widget_row_delete">
        <?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>
        <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=insurances&id='.$insurance->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
        </a>
        <?php endif ; ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

<br />
<?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add (insurance) Value');?>" class="link_catalogue" href="<?php echo url_for('insurances/add?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>
<?php endif ; ?>
