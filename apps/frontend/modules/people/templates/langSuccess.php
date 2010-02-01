<div id="lang_screen">
<form class="edition" action="<?php echo url_for('people/lang?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="lang_form">
<?php echo $form['person_user_ref'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['language_country']->renderLabel();?></th>
      <td>
        <?php echo $form['language_country']->renderError(); ?>
        <?php echo $form['language_country'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['mother']->renderLabel();?></th>
      <td>
        <?php echo $form['mother']->renderError(); ?>
        <?php echo $form['mother'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['prefered_language']->renderLabel();?></th>
      <td>
        <?php echo $form['prefered_language']->renderError(); ?>
        <?php echo $form['prefered_language'];?>
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
	  hideForRefresh($('#lang_screen'));
	  $.ajax({
	    url: '<?php echo url_for('people/deleteLang?id='.$form->getObject()->getId())?>',
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

    $('form#lang_form').submit(function () {
      $('form#lang_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#lang_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    $('form#lang_form').parent().before(html).remove();
	  }
      });
      return false;
    });

  });
</script>
</div>