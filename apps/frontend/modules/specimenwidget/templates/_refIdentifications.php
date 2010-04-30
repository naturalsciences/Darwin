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
  <tbody id="identifications">
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

function forceHelper(e,ui)
{
   $(".ui-state-highlight").html("<td colspan='6'>&nbsp;</td>");
}

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
        visibles = $(parent).find('tbody#identifications').find('tr').size();
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ (0+$('.property_values tbody tr').length) + '/order_by/' + (visibles+1),
          success: function(html)
          {
            $(parent).find('tbody').append(html);
            $(parent).find('thead:hidden').show();
          }
        });
        return false;
    });
    
  $("#identifications").sortable({
      placeholder: 'ui-state-highlight',
      handle: '.handle',
      axis: 'y',
      change: function(e, ui) {
	forceHelper(e,ui);
      },
      deactivate: function(event, ui) {
        $(this).find('tr:visible').each(function (index, item) 
                                        {
                                          $(item).find('input[id$=\"_order_by\"]').val(index+1);
                                        }
                                       );
      }
    });

});
</script>