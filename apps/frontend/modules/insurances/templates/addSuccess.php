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
        <a href="#" class="cancel_qtip">Cancel</a>
        <?php if(! $form->getObject()->isNew()):?>
          <button id="delete"><?php echo __('Delete');?></button>
        <?php endif;?> 
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>
</form>

<script  type="text/javascript">
   $(document).ready(function () {
    $("#delete").click(function(){
      if(confirm('<?php echo __('Are you sure?');?>'))
	{
	  hideForRefresh($('#insurances_screen'));
	  $.ajax({
	    url: '<?php echo url_for('catalogue/deleteRelated?table=insurances&id='.$form->getObject()->getId())?>',
	    success: function(html){
	      if(html == "ok" )
	      {
		$('.qtip-button').click();
	      }
	      else
	      {
		addError(html);
	      }
	    },
	  });
	}
      return false;
    });

    $('form#insurances_form').submit(function () {
      $('form#insurances_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#insurances_screen'));
      $.ajax({
          type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
            $('form#insurances_form').parent().before(html).remove();
            if(!$("ul.error_list li").text())
            {
              $('.qtip-button').click();
            }
	  }});
	return false;
      });

  });
</script>
</div>