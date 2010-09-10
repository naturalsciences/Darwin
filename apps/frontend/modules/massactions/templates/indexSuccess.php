<?php slot('title','Mass Actions');?>
<div class="page" id="mass_action">
  <h1><?php echo __('Mass Actions :');?></h1>
  <table>
    <tbody>
      <tr>
        <th><?php echo $form['source']->renderLabel();?></th>
        <td><?php echo $form['source'];?></td>
      </tr>
      <tr>
        <td colspan="2">
          <div  id="item_list"><?php echo $form['item_list'];?>
          </div>
        </td>
      </tr>

      <tr>
        <th><?php echo $form['action']->renderLabel();?></th>
        <td><?php echo $form['action'];?></td>
      </tr>

      <tr id="action_sub_form">
        <td colspan="2" >
          <div><?php echo $form['MassActionForm'];?></div>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"><input type="submit" value="<?php echo __('Go');?>" /></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript">
function chooseSource()
{
  if($('#mass_action_source').val() == '')
  {
    $('#item_list').html('');
    $('#item_list').addClass('disabled');
    checkItem();
  }
  else
  {
    $('#item_list').load('<?php echo url_for('massactions/items');?>/source/' + $('#mass_action_source').val() , function() {
      checkItem();
    });

    $('#mass_action_action').load('<?php echo url_for('massactions/getActions');?>/source/' + $('#mass_action_source').val() ,function(){});
  }
}

function checkItem()
{
  $('#mass_action_action').val('');
  if( $('#item_list .item_row').length == 0)
  {
    $('#mass_action_action').attr('disabled','disabled');
    $('#mass_action_action').closest('tr').addClass('disabled');
  }
  else
  {
    $('#item_list').removeClass('disabled');
    $('#mass_action_action').removeAttr('disabled');
    $('#mass_action_action').closest('tr').removeClass('disabled');
  }
  chooseAction();
}

function chooseAction()
{
  if($('#mass_action_action').val() == '' || $('#mass_action_action').val() == null)
  {
    $('#action_sub_form').addClass('disabled');
    $('#action_sub_form div').html('');
  }
  else
  {
    $('#action_sub_form').removeClass('disabled');
    $('#action_sub_form > td > div').load('<?php echo url_for('massactions/getSubForm');?>/source/' + $('#mass_action_source').val() + '/action/' + $('#mass_action_action').val() , function() {});
  }
}

$(document).ready(function () {

  chooseSource();
  $('#mass_action_source').change(chooseSource);
  $('#mass_action_action').change(chooseAction);

});

</script>