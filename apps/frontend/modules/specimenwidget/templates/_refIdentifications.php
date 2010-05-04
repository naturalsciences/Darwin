<table class="property_values" id="identifications">
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
    <?php foreach($form['Identifications'] as $form_value):?>
      <?php include_partial('spec_identifications', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newIdentification'] as $form_value):?>
      <?php include_partial('spec_identifications', array('form' => $form_value));?>
    <?php endforeach;?>
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
   $(".ui-state-highlight").html("<td colspan='6' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");
}

function reOrderIdent()
{
  $('table#identifications').find('tbody.spec_ident_data:visible').each(function (index, item){
    $(item).find('tr.spec_ident_data input[id$=\"_order_by\"]').val(index+1);
  });
}


$(document).ready(function () {

    $('.clear_identification').live('click', function()
    {
      parent = $(this).closest('tbody');
      nvalue='';
      $(parent).find('input[id$=\"_value_defined\"]').val(nvalue);
      $(parent).hide();
      reOrderIdent();
      visibles = $('table#identifications tbody.spec_ident_data:visible').size();
      if(!visibles)
      {
        $(this).closest('table#identifications').find('thead').hide();
      }
    });

    $('#add_identification').click(function()
    {
        parent = $(this).closest('table#identifications');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ ($('tbody.spec_ident_data').length) + '/order_by/' + ($('tbody.spec_ident_data:visible').length+1),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead:hidden').show();
          }
        });
        return false;
    });
    

 $("#identifications").sortable({
      placeholder: 'ui-state-highlight',
      handle: '.spec_ident_handle',
      axis: 'y',
      change: function(e, ui) {
	forceHelper(e,ui);
      },
      deactivate: function(event, ui) {
        reOrderIdent();
      }
    });

				
});
</script>