<?php slot('title','Mass Actions');?>
<div class="page" id="mass_action">
  <h1><?php echo __('Mass Actions :');?></h1>
  <?php echo form_tag('massactions/index', array('autocomplete'=>"off"));?>
  <table>
    <tbody>
      <tr>
        <th><?php echo $form['source']->renderLabel();?></th>
        <td><?php echo $form['source'];?></td>
      </tr>
      <tr>
        <td colspan="2">
          <div  id="item_list">
            <?php if(isset($items)):?>
              <?php include_partial('itemlist',array('items'=>$items));?>
            <?php else:?>
              <?php echo $form['item_list'];?>
            <?php endif;?>
          </div>
        </td>
      </tr>

      <tr>
        <th><?php echo $form['field_action']->renderLabel();?></th>
        <td><?php echo $form['field_action'];?></td>
      </tr>

      <tr id="action_sub_form">
        <td colspan="2" >
          <div>
            <?php include_partial('subform',array('form'=>$form));?>
          </div>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"><input type="submit" id="mass_submit" value="<?php echo __('Go');?>" /></td>
      </tr>
    </tfoot>
  </table>
  </form>
</div>
<script type="text/javascript">
function chooseSource(event)
{
  if(! event  && $('#mass_action_source').val() != '')
    return;
  if($('#mass_action_source').val() == '')
  {
    $('#item_list').html('');
    $('#item_list').addClass('disabled');
    checkItem();
  }
  else
  {
    $("#item_list").html('<img src="<?php echo image_path('loader.gif');?>" />');
    $('#item_list').load('<?php echo url_for('massactions/items');?>/source/' + $('#mass_action_source').val() , function() {
      checkItem();
    });
    $('#mass_action_field_action').load('<?php echo url_for('massactions/getActions');?>/source/' + $('#mass_action_source').val() ,function(){});
  }
}

function checkItem()
{
  if( $('#item_list .item_row').length == 0)
  {
    $('#mass_action_field_action').val('');
    $('#mass_action_field_action').attr('disabled','disabled');
    $('#mass_action_field_action').closest('tr').addClass('disabled');
    chooseAction();
  }
  else
  {
    $('#item_list').removeClass('disabled');
    $('#mass_action_field_action').removeAttr('disabled');
    $('#mass_action_field_action').closest('tr').removeClass('disabled');
  }
}

function chooseAction()
{
  if($('#mass_action_field_action').val() == '' || $('#mass_action_field_action').val() == null)
  {
    $('#action_sub_form').addClass('disabled');
    $('#action_sub_form div').html('');
  }
  else
  {
    $('#action_sub_form').removeClass('disabled');
    $("#action_sub_form > td > div").html('<img src="<?php echo image_path('loader.gif');?>" />');
    $('#action_sub_form > td > div').load('<?php echo url_for('massactions/getSubForm');?>/source/' + $('#mass_action_source').val() + '/maction/' + $('#mass_action_field_action').val() , function() {});
  }
  changeSubmit(false);
}

function changeSubmit(status)
{
  if(status)
    $('#mass_submit').removeAttr('disabled');
  else
    $('#mass_submit').attr('disabled','disabled');
}

$(document).ready(function () {

  chooseSource();
  $('#mass_action_source').change(chooseSource);
  $('#mass_action_field_action').change(chooseAction);
  $('#mass_submit').closest('form').submit(function (event)
  {

    if(! confirm('<?php echo __('Are you sure ?') ?>'))
    {
      event.preventDefault();
    }
  });

});

</script>