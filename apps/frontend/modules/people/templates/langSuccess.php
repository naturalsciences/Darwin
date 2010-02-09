<div id="lang_screen">
<form class="edition" action="<?php echo url_for('people/lang?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="lang_form">
<?php echo $form['people_ref'];?>
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
      <th><?php echo $form['preferred_language']->renderLabel();?></th>
      <td>
        <?php echo $form['preferred_language']->renderError(); ?>
        <?php echo $form['preferred_language'];?>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	   <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=people_languages&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
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
	    else
	    {
	      $('form#lang_form').parent().before(html).remove();
	    }
	  }
      });
      return false;
    });

  });
</script>
</div>
