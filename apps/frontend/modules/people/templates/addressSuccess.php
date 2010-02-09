<div id="address_screen">
<form class="edition" action="<?php echo url_for('people/address?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="address_form">
<?php echo $form['person_user_ref'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
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
      <th><?php echo $form['po_box']->renderLabel();?></th>
      <td>
        <?php echo $form['po_box']->renderError(); ?>
        <?php echo $form['po_box'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['extended_address']->renderLabel();?></th>
      <td>
        <?php echo $form['extended_address']->renderError(); ?>
        <?php echo $form['extended_address'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['locality']->renderLabel();?></th>
      <td>
        <?php echo $form['locality']->renderError(); ?>
        <?php echo $form['locality'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['zip_code']->renderLabel();?></th>
      <td>
        <?php echo $form['zip_code']->renderError(); ?>
        <?php echo $form['zip_code'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['region']->renderLabel();?></th>
      <td>
        <?php echo $form['region']->renderError(); ?>
        <?php echo $form['region'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['country']->renderLabel();?></th>
      <td>
        <?php echo $form['country']->renderError(); ?>
        <?php echo $form['country'];?>
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
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	   <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=people_addresses&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
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

    $('form#address_form').submit(function () {
      $('form#address_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#address_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    $('form#address_form').parent().before(html).remove();
	  }
      });
      return false;
    });

  });
</script>
</div>
