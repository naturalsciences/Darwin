<div id="comment_screen">
<form class="edition" method="post" action="<?php echo url_for('comment/comment?table='.$sf_params->get('table'). ($form->getObject()->isNew() ? '' : '&cid='.$form->getObject()->getId() ) );?>" id="comment_form">
<table>
  <tbody>
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
  </tbody>
  <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          &nbsp;<a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
	    <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=comments&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
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
  $(document).ready(function () 
  {
    $('form#comment_form').submit(function () {
      $('form#comment_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#comment_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    $('form#comment_form').parent().before(html).remove();
	  }
      });
      return false;
    });
  });
</script>

</div>