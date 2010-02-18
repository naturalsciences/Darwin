<?php include_javascripts_for_form($form) ?>
<div id="syn_screen">
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
      }
      else
      {
	$('#catalogue_people_people_sub_type_parent .add_item_button').show();
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

<div class="search_box show">
  <?php include_partial('people/searchForm', array('form' => $searchForm, 'is_choose' => true)) ?>
</div>

</div>