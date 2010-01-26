<div id="comm_screen">
<form class="edition" action="<?php echo url_for('people/comm?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="comm_form">
<?php echo $form['person_user_ref'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['comm_type']->renderLabel();?></th>
      <td>
        <?php echo $form['comm_type']->renderError(); ?>
        <?php echo $form['comm_type'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['entry']->renderLabel();?></th>
      <td>
        <?php echo $form['entry']->renderError(); ?>
        <?php echo $form['entry'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['tag']->renderLabel();?></th>
      <td>
        <?php echo $form['tag']->renderError(); ?>
        <?php echo $form['tag'];?>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
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

      $("#delete").click(function()
      {
	if(confirm('<?php echo __('Are you sure?');?>'))
	{
	  hideForRefresh($('#comm_screen'));
	  $.ajax({
	    url: '<?php echo url_for('people/deleteComm?id='.$form->getObject()->getId())?>',
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

    $('form#comm_form').submit(function () {
      $('form#comm_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#comm_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    $('form#comm_form').parent().before(html).remove();
	  }
      });
      return false;
    });

  });
</script>
</div>