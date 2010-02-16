<?php include_javascripts_for_form($form) ?>
<div id="comm_screen">
<form class="edition qtiped_form" action="<?php echo url_for('user/comm?ref_id='.$sf_request->getParameter('ref_id') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="comm_form">
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
      <th class="top_aligned"><?php echo $form['tag']->renderLabel();?></th>
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
	  <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=users_comm&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
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

    $('#users_comm_comm_type').change(function () {
      $('form#lang_form input[type=submit]').attr('disabled','disabled');
      $.ajax({
	  type: "get",
	  url: "<?php echo url_for('user/getTags');?>/type/" + $('#users_comm_comm_type').val(),
	  success: function(html){
	    $('#users_comm_tag').val('');
	    $('#users_comm_tag_selected').html('');
	    $('#users_comm_tag_available').html(html);
	  }
      });
    });
  });
</script>
</div>
