<table class="catalogue_table_view">
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
  	    <?php echo $insurance->getFormatedInsuranceValue();?>
      </td>
      <td><?php echo $insurance->getFormatedInsuranceYear();?></td>
      <td>
        <?php if($insurance->People): ?>
          <?php echo $insurance->People->getFamilyName();?>
        <?php else: ?>
          <?php echo '-'; ?>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
