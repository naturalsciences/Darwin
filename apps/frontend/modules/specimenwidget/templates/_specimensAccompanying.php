<table  class="property_values">
  <thead style="<?php echo ($form['SpecimensAccompanying']->count() || $form['newSpecimensAccompanying']->count())?'':'display: none;';?>">
    <tr>
      <th>
        <?php echo __('Type'); ?>
      </th>
      <th>
        <?php echo __('Form'); ?>
      </th>
      <th colspan="2">
        <?php echo __('Quantity'); ?>
      </th>
      <th>
	<?php echo $form['accompanying'];?>
      </th>
    </tr>
  </thead>
  <tbody id="specimens_accompanying">
    <?php foreach($form['SpecimensAccompanying'] as $form_value):?>
      <?php include_partial('specimen/specimens_accompanying', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newSpecimensAccompanying'] as $form_value):?>
      <?php include_partial('specimen/specimens_accompanying', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='5'>
        <div class="add_code">
          <a href="<?php echo url_for('specimen/addSpecimensAccompanying'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_accompanying"><?php echo __('Add element');?></a>
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
      biological = parent.next('tr.biological');
      mineral = parent.next('tr.mineral');
      nvalue='';
      tvalue = '-';
      bvalue = 'Change !';
      $(biological).find('input[id$=\"_taxon_ref\"]').val(nvalue);
      $(biological).find('input[id$=\"_taxon_ref_name\"]').val(tvalue);
      $(biological).find('div[id$=\"_taxon_ref_button\"]').find('a').html(bvalue);
      $(mineral).find('input[id$=\"_mineral_ref\"]').val(nvalue);
      $(mineral).find('input[id$=\"_mineral_ref_name\"]').val(tvalue);
      $(mineral).find('div[id$=\"_mineral_ref_button\"]').find('a').html(bvalue);
      $(parent).find('input[id$=\"_quantity\"]').val(nvalue);
      $(parent).hide();
      $(biological).hide();
      $(mineral).hide();
      visibles = $(parent).closest('tbody').find('tr:visible').size();
      if(!visibles)
      {
        $(this).closest('table.property_values').find('thead').hide();
      }
    });

    $('#add_accompanying').click(function()
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