<div id="syn_screen">
<form action="<?php echo url_for('synonym/index?table='.$sf_request->getParameter('table').'&id='.$sf_request->getParameter('id') . ( ! $sf_request->hasParameter('group_id') ? '': '&group_id='.$sf_request->getParameter('group_id') ) );?>" method="post" id="synonym_form">
<table>
  <tr>
      <td colspan="2">
        <?php echo $form->renderGlobalErrors() ?>
      </td>
  </tr>
  <tr>
    <th><?php echo $form['group_name']->renderLabel();?></th>
    <td>
      <?php echo $form['group_name']->renderError(); ?>
      <?php echo $form['group_name'];?>
    </td>
  </tr>
  <tr class="basionym_raw">
    <th><?php echo $form['is_basionym']->renderLabel();?></th>
    <td>
      <?php echo $form['is_basionym']->renderError(); ?>
      <?php echo $form['is_basionym'];?>
    </td>
  </tr>
  <tr>
    <th><?php echo $form['order_by']->renderLabel();?></th>
    <td>
      <?php echo $form['order_by']->renderError(); ?>
      <?php echo $form['order_by'];?>
    </td>
  </tr>

  <tr>
    <th><?php echo $form['record_id']->renderLabel('Item');?></th>
    <td>
      <?php echo $form['record_id']->renderError(); ?>
      <?php echo $form['record_id'];?>
      <span class="synonym_name"></span>
    </td>
  </tr>

  <tr class="<?php if(! $form['merge']->hasError()) echo 'hidden';?> merge_question">
   <th><?php echo $form['merge']->renderLabel('Confirm');?></th>
   <td>
      <?php echo $form['merge']->renderError(); ?>
      <div><?php echo __('This element already has synonyms.<br />Are you sure that you want to merge them together?');?></div>
      <?php echo __('Yes ');?> <?php echo $form['merge'];?>
    </td>
  </tr>

  <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          &nbsp;<a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            <button id="delete"><?php echo __('Delete');?></button>
          <?php endif; ?>
          <input id="save" name="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
  </tfoot>  
</table>
</form>

<script type="text/javascript">

function checkGroup()
{
  if($("#classification_synonymies_record_id").val() == '')
    return;

  $('#save').attr("disabled","disabled");
  $.ajax({
      url: '<?php echo url_for('synonym/checks?table='.$sf_request->getParameter('table'))?>/id/'+$("#classification_synonymies_record_id").val()+'/type/'+$("#classification_synonymies_group_name").val(),
      success: function(html){
	$('#save').removeAttr("disabled");
	if(html == "0" )
	{
	  $(".merge_question").hide();
	  $(".merge_question input").attr('checked', true);
	}
	else
	{
	  $(".merge_question input").attr('checked', false);
	  $(".merge_question").show();
	}
      },
      error: function(xhr)
      {
	$('#save').removeAttr("disabled");
      }
   });
}

function showBasionym()
{
  if($('#classification_synonymies_group_name').val() == "homonym")
    $('.basionym_raw').hide();
  else
    $('.basionym_raw').show();
}

  $(document).ready(function () {
    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	$("#classification_synonymies_record_id").val(getIdInClasses(el));
	$("#classification_synonymies_record_id_name").text(el.find('span.item_name').text()).show();
	checkGroup();
    });

    showBasionym();

    $('#classification_synonymies_group_name').change(checkGroup);
    $('#classification_synonymies_group_name').change(showBasionym);

    $('form#synonym_form').submit(function () {
      $('form#synonym_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#syn_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    else
	    {
	      $('form#synonym_form').parent().before(html).remove();
	    }
	  }
      });
      return false;
    });
});
</script>

<div class="search_box show">
  <?php include_partial('catalogue/chooseItem', array('searchForm' => $searchForm, 'is_choose' => true)) ?>
</div>
</div>