<table class="property_values">
  <thead style="<?php echo ($form['Identifications']->count() || $form['newIdentification']->count())?'':'display: none;';?>">
    <tr>
      <th>
        <?php echo $form['ident'];?>
      </th>
      <th>
        <?php echo __('Date'); ?>
      </th>
      <th>
        <?php echo __('Subject'); ?>
      </th>
      <th>
        <?php echo __('Value'); ?>
      </th>
      <th>
        <?php echo __('Det. St.'); ?>
      </th>
      <th>
      </th>
    </tr>
  </thead>
  <tbody class="codes">
    <?php foreach($form['Identifications'] as $form_value):?>
      <?php include_partial('spec_identifications', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newIdentification'] as $form_value):?>
      <?php include_partial('spec_identifications', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='6'>
        <div class="add_code">
          <a href="<?php echo url_for('specimen/addIdentification'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_identification"><?php echo __('Add Ident.');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
<script  type="text/javascript">
$(document).ready(function () {

    $('.clear_prop').live('click', function()
    {
      parent = $(this).closest('tr');
      nvalue='';
      $(parent).find('input[id$=\"_value_defined\"]').val(nvalue);
      $(parent).hide();
      visibles = $(parent).closest('tbody').find('tr:visible').size();
      if(!visibles)
      {
        $(this).closest('table.property_values').find('thead').hide();
      }
    });

    $('#add_identification').click(function()
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