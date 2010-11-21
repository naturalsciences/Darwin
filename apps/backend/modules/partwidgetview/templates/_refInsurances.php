<table  class="catalogue_table_view">
  <thead style="<?php echo ($Insurances->count()?'':'display: none;');?>">
    <tr>
      <th>
        <?php echo __('Year'); ?>
      </th>
      <th>
        <?php echo __('Value'); ?>
      </th>
      <th>
        <?php echo __('Currency'); ?>
      </th>
      <th>
      	<?php echo __('Insurer');?>
      </th>
    </tr>
  </thead>
    <?php foreach($Insurances as $insurance):?>
    <tbody  class="parts_insurances_data"">
      <tr>
        <td>
          <?php echo $insurance->getInsuranceYear();?>
        </td>
        <td>
          <?php echo $insurance->getInsuranceValue();?>
        </td>
        <td>
          <?php echo $insurance->getInsuranceCurrency();?>
        </td>
        <td>
           <?php echo $insurance->getInsurerRef() == ''?'-':$insurance->People->getFormatedName() ; ?>
        </td>    
      </tr>
    </tbody>
    <?php endforeach;?>
</table>

