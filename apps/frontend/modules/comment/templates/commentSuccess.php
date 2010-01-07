<ul class="hidden error_list">
  <li></li>
</ul>

<script type="text/javascript">
    function addError(html)
    {
      $('.error_list li').text(html);
      $('.error_list').show();
    }
    function removeError()
    {
	$('.error_list').hide();
	$('.error_list li').text(' ');
    }
$("#comment_form").submit(function()
      {
	removeError();
	$.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
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
	  error: function(xhr)
	  {
	    addError('Error!  Status = ' + xhr.status);
	  }});
	return false;
      });

      $("#delete").click(function()
      {
	if(confirm('<?php echo __('Are you sure?');?>'))
	{
	  removeError();
	  $.ajax({
	    url: '<?php echo url_for('comment/delete?id='.$form->getObject()->getId())?>',
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
	    error: function(xhr)
	    {
	      addError('Error!  Status = ' + xhr.status);
	    }});
	}
	return false;
      });
</script>
<form method="post" action="<?php echo url_for('comment/comment?table='.$sf_params->get('table'). ($form->getObject()->isNew() ? '' : '&cid='.$form->getObject()->getId() ) );?>" id="comment_form">
<?php echo $form['referenced_relation'];?>
<?php echo $form['record_id'];?>
<?php echo $form['id'];?>
<table>
  <tr>
      <td colspan="2">
        <?php echo $form->renderGlobalErrors() ?>
      </td>
  </tr>
  <tr>
    <th><?php echo $form['notion_concerned']->renderLabel();?></th>
    <td>
      <?php echo $form['notion_concerned']->renderError(); ?>
      <?php echo $form['notion_concerned'];?>
  </td>
  <tr>
    <th><?php echo $form['comment']->renderLabel();?></th>
    <td>
      <?php echo $form['comment']->renderError(); ?>
      <?php echo $form['comment'];?>
  </td>
  </tr>
</table>
  <a href="#" class="cancel_qtip">Cancel</a>
  <?php if(! $form->getObject()->isNew()):?><button id="delete"><?php echo __('Delete');?></button><?php endif;?>
  <input type="submit" name="submit" id="save" value="<?php echo __('Save');?>" />
</form>