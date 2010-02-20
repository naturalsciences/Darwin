<?php include_javascripts_for_form($form) ?>
<div id="catalogue_people_screen">
<form class="edition qtiped_form" method="post" action="<?php echo url_for('cataloguepeople/people?table='.$sf_params->get('table'). ($form->getObject()->isNew() ? '' : '&id='.$form->getObject()->getId() ) );?>" id="cataloguepeople_form">
<table >
  <tr>
      <td colspan="2">
        <?php echo $form->renderGlobalErrors() ?>
      </td>
  </tr>
  <tr>
    <th><?php echo $form['people_type']->renderLabel();?></th>
    <td>
      <?php echo $form['people_type']->renderError(); ?>
      <?php echo $form['people_type'];?>
    </td>
  </tr>
  <tr>
    <th><?php echo $form['people_sub_type']->renderLabel();?></th>
    <td>
      <?php echo $form['people_sub_type']->renderError(); ?>
      <?php echo $form['people_sub_type'];?>
    </td>
  </tr>

  <tr>
    <th><?php echo $form['people_ref']->renderLabel();?></th>
    <td>
      <?php echo $form['people_ref']->renderError(); ?>
      <?php echo $form['people_ref'];?>
    </td>
  </tr>
  <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          &nbsp;<a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
	    <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_people&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
	      <?php echo __('Delete');?>
	    </a>
          <?php endif; ?>
          <input id="save" name="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
  </tfoot>  
</table>
</form>

<script type="text/javascript">
function toggleChangingChoice()
{
      if($('#catalogue_people_people_type').val() =='authors')
      {
	$('#catalogue_people_people_sub_type_parent .change_item_button:visible').click();
	$('#catalogue_people_people_sub_type_parent .add_item_button').hide();
	
	$('.both_search_institutions').addClass('disabled');
	
	//if($('.both_search_institutions').hasClass('activated'))
	$('.both_search_people').click();

	$('#only_role').val(2);
      }
      else
      {
	$('#catalogue_people_people_sub_type_parent .add_item_button').show();
	$('.both_search_institutions').removeClass('disabled');
	$('#only_role').val(8);
      }
}
$(document).ready(function () 
{
    toggleChangingChoice();

    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	$("#catalogue_people_people_ref").val(getIdInClasses(el));
	$("#catalogue_people_people_ref_name").text(el.find('.item_name').text()).show();
	console.log(el.find('.item_name').text());
    });

    $('#catalogue_people_people_type').change(function() {
      toggleChangingChoice();
      $.get("<?php echo url_for('cataloguepeople/getSubType');?>/type/"+$(this).val(), function (data) {
	$("#catalogue_people_people_sub_type").html(data);
      });
    });
});
</script>



<input type="hidden" name="only_role" id="only_role" value="0" />

<script language="javascript">

  $(document).ready(function () {
    $('.both_search_people').click(function()
    {
      $('.both_search_institutions').removeClass('activated');

      $(".search_box").html('<?php echo __("Searching");?>');

      people_search_url = '<?php echo url_for('people/choose?with_js=0&is_choose=1');?>';
      $('.both_search_people').addClass('activated');
      $.ajax({
	  type: "POST",
	  url: people_search_url + '/only_role/'+$("#only_role").val(),
	  success: function(html){
	    $('.search_box').html(html);
	  }
      });
    });
    
    $('.both_search_institutions').click(function()
    {
      if( $(this).hasClass('disabled')) return false;
      $('.both_search_people').removeClass('activated');

      $(".search_box").html('<?php echo __("Searching");?>');

      $('.both_search_institutions').addClass('activated');

      $.ajax({
	  type: "POST",
	  url: '<?php echo url_for('institution/choose?with_js=0&is_choose=1&only_role=8');?>',
	  success: function(html){
	    $('.search_box').html(html);
	  }
      });
    });

  });
</script>


<ul class="tab_choice">
  <li class="both_search_people"><?php echo __('People');?></li>
  <li class="both_search_institutions"><?php echo __('Institution');?></li>
</ul>
<div class="search_box show">
  <?php echo __("Choose a type");?>
</div>

</div>