<h1 class="edit_mode"><?php echo __(sprintf("Set rights on widgets for %s", $user->getFormatedName())); ?></h1>
<form action="" method="post">
  <table class="widget_right edition">
    <thead>
      <tr>
      	<th><?php echo __("Category/screen");?></th><th><?php echo __("Name");?></th><th><?php echo __("Allow visibility");?></th>
      </tr>
    </thead>
	  <tbody>
      <?php echo $form['widget_ref']->renderError() ?>
      <?php echo $form['widget_ref'] ?>
	  </tbody>
    <tfoot>
      <tr>
        <td colspan="3"> 
          <input id="reset" type="reset" value="<?php echo __('Reset');?>" />
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<script>
$('#all_category').live('change',function()
{
  $(this).closest('tbody').find('input[type=checkbox]').attr("checked",$(this).attr('checked')) ;
});
</script>
