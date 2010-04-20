<table  class="property_values">
  <thead>
    <tr>
      <th>
        <?php echo __('Category'); ?>
      </th>
      <th>
        <?php echo __('Prefix'); ?>
      </th>
      <th>
        <?php echo __('sep.'); ?>
      </th>
      <th>
        <?php echo __('Num. code'); ?>
      </th>
      <th>
        <?php echo __('sep.'); ?>
      </th>
      <th>
        <?php echo __('Suffix'); ?>
      </th>
      <th>
      </th>
    </tr>
    <tr>
      <th colspan='2'>
      </th>
      <th class="reseted">
        <?php echo $form['prefix_separator'];?>
      </th>
      <th>
      </th>
      <th class="reseted">
        <?php echo $form['suffix_separator'];?>
      </th>
      <th colspan='3'>
      </th>
    </tr>
  </thead>
  <tbody class="codes">
    <?php foreach($form['SpecimensCodes'] as $form_value):?>
      <?php include_partial('spec_codes', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newCode'] as $form_value):?>
      <?php include_partial('spec_codes', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='8'>
        <div class="add_code">
          <a href="<?php echo url_for('specimen/addCode'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_prop_value"><?php echo __('Add Code');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
<script  type="text/javascript">
$(document).ready(function () {

    $('.clear_prop').live('click', clearPropertyValue);

    $('#add_prop_value').click(addPropertyValue);
    
    $('select[id$=\"_prefix_separator\"]').change(updateVals('_prefix_separator', $(this).val()));

});
</script>