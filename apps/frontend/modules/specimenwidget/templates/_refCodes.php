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
        <?php echo $form['code'];?>
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

    $('#add_prop_value').click(function()
    {
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ (0+$('.property_values tbody tr').length) + '/collection_id/' + $('input#specimen_collection_ref').val(),
          success: function(html)
          {
            $('.property_values tbody').append(html);
          }
        });
        return false;
    });
    
    $('select#specimen_prefix_separator').change(function()
    {
      parent = $(this).closest('table');
      $(parent).find('tbody select[id$=\"_prefix_separator\"]').val($(this).val());
    }
    );

    $('select#specimen_suffix_separator').change(function()
    {
      parent = $(this).closest('table');
      $(parent).find('tbody select[id$=\"_suffix_separator\"]').val($(this).val());
    }
    );

});
</script>