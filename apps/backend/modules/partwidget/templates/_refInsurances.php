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
    <?php $retainedKey = 0;?>
    <?php foreach($form['Insurances'] as $form_value):?>
      <?php include_partial('parts/insurances', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
    <?php foreach($form['newInsurance'] as $form_value):?>
      <?php include_partial('parts/insurances', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
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
    $('#add_insurance').click(function()
    {
        parent = $(this).closest('table.property_values');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ (0+$(parent).find('tbody').length),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead:hidden').show();
          }
        });
        return false;
    });
  });
</script>