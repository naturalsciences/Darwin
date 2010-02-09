<div id="relation_screen">
<form class="edition" action="<?php echo url_for('people/relation?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="relation_form">
<?php echo $form['person_2_ref'];?>
<?php echo $form['id'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['person_1_ref']->renderLabel('Institution');?></th>
      <td>
        <?php echo $form['person_1_ref']->renderError(); ?>
        <?php echo $form['person_1_ref'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['relationship_type']->renderLabel();?></th>
      <td>
        <?php echo $form['relationship_type']->renderError(); ?>
        <?php echo $form['relationship_type'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['person_user_role']->renderLabel('Role in the organisation');?></th>
      <td>
        <?php echo $form['person_user_role']->renderError(); ?>
        <?php echo $form['person_user_role'];?>
      </td>
    <tr>
      <th><?php echo $form['activity_date_from']->renderLabel();?></th>
      <td>
        <?php echo $form['activity_date_from']->renderError(); ?>
        <?php echo $form['activity_date_from'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['activity_date_to']->renderLabel();?></th>
      <td>
        <?php echo $form['activity_date_to']->renderError(); ?>
        <?php echo $form['activity_date_to'];?>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	  <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=people_relationships&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
	    <?php echo __('Delete');?>
	  </a>
        <?php endif;?> 
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>

</form>

<script  type="text/javascript">
  $(document).ready(function () {
    $('form#relation_form').submit(function () {
      $('form#relation_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#relation_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    $('form#relation_form').parent().before(html).remove();
	  }
      });
      return false;
    });

    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	$("#people_relationships_person_1_ref").val(getIdInClasses(el));
	console.log(el.find('.item_name'));
	$("#people_relationships_person_1_ref_name").text(el.find('.item_name').text()).show();
    });
});
</script>

<div class="search_box show">
  <?php include_partial('institution/searchForm', array('form' => new InstitutionsFormFilter(),'is_choose'=>true)) ?>
</div>

</div>
