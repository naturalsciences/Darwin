<table  class="property_values">
  <thead style="<?php echo ($form['Codes']->count() || $form['newCode']->count())?'':'display: none;';?>">
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
        <?php echo __('Code'); ?>
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
    <?php $retainedKey = 0;?>
    <?php foreach($form['Codes'] as $form_value):?>
      <?php include_partial('specimen/spec_codes', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
    <?php foreach($form['newCode'] as $form_value):?>
      <?php include_partial('specimen/spec_codes', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
  <tfoot>
    <tr>
      <td colspan='8'>
        <div class="add_code">
          <a href="<?php echo url_for('specimen/addCode'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_code"><?php echo __('Add code');?></a>
        </div>
      </td>
    </tr>
  </tfoot>

</table>
<script  type="text/javascript">
$(document).ready(function () {

    $('#add_code').click(function()
    {
        hideForRefresh('#refCodes');
        parent = $(this).closest('table.property_values');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ (0+$(parent).find('tbody').length) + '/collection_id/' + $('input#specimen_collection_ref').val(),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead:hidden').show();
            showAfterRefresh('#refCodes');
          }
        });
        return false;
    });
    
    $('select#specimen_prefix_separator').change(function()
    {
      parent = $(this).closest('table');
      $(parent).find('tbody select[id$=\"_prefix_separator\"]').val($(this).val());
    });

    $('select#specimen_suffix_separator').change(function()
    {
      parent = $(this).closest('table');
      $(parent).find('tbody select[id$=\"_suffix_separator\"]').val($(this).val());
    });

});
</script>
