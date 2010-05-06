<?php $spec_id = ($form->getObject()->isNew() ? 0: $form->getObject()->getId());?>
<table class="property_values" id="identifications">
  <thead style="<?php echo ($form['Identifications']->count() || $form['newIdentification']->count())?'':'display: none;';?>" class="spec_ident_head">
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
    <?php foreach($form['Identifications'] as $form_key=>$form_value):?>
      <?php include_partial('spec_identifications', array('form' => $form_value, 'row_num'=>$form_key, 'spec_id'=>$spec_id));?>
    <?php endforeach;?>
    <?php foreach($form['newIdentification'] as $form_key=>$form_value):?>
      <?php include_partial('spec_identifications', array('form' => $form_value, 'row_num'=>$form_key, 'spec_id'=>$spec_id));?>
    <?php endforeach;?>
  <tfoot>
    <tr>
      <td colspan='6'>
        <div class="add_code">
          <a href="<?php echo url_for('specimen/addIdentification'. ($form->getObject()->isNew() ? '': '?spec_id='.$form->getObject()->getId()) );?>/num/" id="add_identification"><?php echo __('Add Ident.');?></a>
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

function reOrderIdentifiers(tableId)
{
  $('table#'+tableId).find('tbody.spec_ident_identifiers_data:visible').each(function (index, item){
    $(item).find('tr.spec_ident_identifiers_data input[id$=\"_order_by\"]').val(index+1);
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
        $(this).closest('table#identifications').find('thead.spec_ident_head').hide();
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
            $(parent).find('thead.spec_ident_head:hidden').show();
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

    $('.clear_identifier').live('click', function()
    {
      parent = $(this).closest('tbody');
      parentTableId = $(parent).closest('table').attr('id');
      nvalue='';
      tvalue='-';
      bvalue='Choose !';
      $(parent).find('input[id$=\"_people_ref\"]').val(nvalue);
      $(parent).find('div[id$=\"_people_ref_name\"]').text(tvalue);
      $(parent).find('div[id$=\"_people_ref_button\"]').find('a').text(bvalue);
      $(parent).hide();
      reOrderIdentifiers(parentTableId);
      visibles = $('table#'+parentTableId+' tbody.spec_ident_identifiers_data:visible').size();
      if(!visibles)
      {
        $(this).closest('table#'+parentTableId).find('thead').hide();
        $('table#'+parentTableId).toggleClass('green_border',false);
      }
    });

    $('.add_identifier').live('click', function()
    {
        parent = $(this).closest('table.identifiers');
        parentId = $(parent).attr('id');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ ($('table#'+parentId+' tbody.spec_ident_identifiers_data').length) + '/iorder_by/' + ($('table#'+parentId+' tbody.spec_ident_identifiers_data:visible').length+1),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead:hidden').show();
            $('table#'+parentId).toggleClass('green_border',true);
          }
        });
        return false;
    });
    

});
</script>