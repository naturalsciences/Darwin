<table  class="property_values">
  <thead style="<?php echo ($form['Insurances']->count() || $form['newInsurance']->count())?'':'display: none;';?>">
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
	<?php echo $form['insurance'];?>
      </th>
    </tr>
  </thead>
  <tbody id="insurances">
    <?php foreach($form['Insurances'] as $form_value):?>
      <?php include_partial('parts/insurances', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newInsurance'] as $form_value):?>
      <?php include_partial('parts/insurances', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='4'>
        <div class="add_code">
          <a href="<?php echo url_for('parts/addInsurance'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_insurance"><?php echo __('Add Insurance');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
<script  type="text/javascript">
$(document).ready(function () {

    $('.clear_code').live('click', function()
    {
      parent = $(this).closest('tr');
      nvalue='';
      $(parent).find('input[id$=\"_insurance_value\"]').val(nvalue);
      $(parent).hide();
      $(parent).next('tr').hide();
      visibles = $(parent).closest('tbody').find('tr:visible').size();
      if(!visibles)
      {
        $(this).closest('table.property_values').find('thead').hide();
      }
    });

    $('#add_insurance').click(function()
    {
        parent = $(this).closest('table.property_values');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ (0+$('.property_values tbody tr').length),
          success: function(html)
          {
            $(parent).find('tbody').append(html);
            $(parent).find('thead:hidden').show();
          }
        });
        return false;
    });

});
</script>