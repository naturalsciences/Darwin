<div id="insurances_screen">

<?php if (isset($message)): ?>
  <div class="flash_save"><?php echo __($message); ?></div>
<?php endif; ?>
<form id="insurances_form" class="edition" action="<?php echo url_for('insurances/add?table='.$sf_request->getParameter('table').'&id='.$sf_request->getParameter('id') . ($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() ) );?>" method="post">
<?php echo $form['referenced_relation'];?>
<?php echo $form['record_id'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['insurance_value']->renderLabel();?></th>
      <td>
        <?php echo $form['insurance_value']->renderError(); ?>
        <?php echo $form['insurance_value'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['insurance_currency']->renderLabel();?></th>
      <td>
        <?php echo $form['insurance_currency']->renderError(); ?>
        <?php echo $form['insurance_currency'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['insurance_year']->renderLabel();?></th>
      <td>
        <?php echo $form['insurance_year']->renderError(); ?>
        <?php echo $form['insurance_year'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['insurer_ref']->renderLabel();?></th>
      <td>
        <?php echo $form['insurer_ref']->renderError(); ?>
        <?php echo $form['insurer_ref'];?>
      </td>
    </tr>
  </tbody>
</table>
<table class="bottom_actions">
  <tfoot>
    <tr>
      <td>
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	  <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=insurances&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
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

    $('form#insurances_form').submit(function () {
      $('form#insurances_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#insurances_screen'));
      $.ajax({
          type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
            if(html == 'ok')
            {
              $('.qtip-button').click();
            }
            $('form#insurances_form').parent().before(html).remove();
	  }});
	return false;
      });

    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	$("#insurances_insurer_ref").val(getIdInClasses(el));
	console.log(el.find('.item_name'));
	$("#insurances_insurer_ref_name").text(el.find('.item_name').text()).show();
    });
});
</script>

<div class="search_box show">
  <?php include_partial('institution/searchForm', array('form' => new InstitutionsFormFilter(),'is_choose'=>true)) ?>
</div>

</div>
