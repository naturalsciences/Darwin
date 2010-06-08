<form class="edition" method="post" id="collection_maintenance" action="<?php echo url_for('maintenance/index');?>">
	<h2><?php echo __('2. Add Maintenance item : ');?></h2>
	<?php include_stylesheets_for_form($form) ?>
	<?php include_javascripts_for_form($form) ?>
	
	<?php echo $form->renderGlobalErrors() ?>

	<table>
	<tbody>
	  <tr>
		<th><?php echo $form['category']->renderLabel();?></th>
		<td>
		  <?php echo $form['category']->renderError() ?>
		  <?php echo $form['category'];?>
		</td>
	  </tr>
	  <tr>
		<th><?php echo $form['action_observation']->renderLabel();?></th>
		<td>
		  <?php echo $form['action_observation']->renderError() ?>
		  <?php echo $form['action_observation'];?>
		</td>
	  </tr>
	  <tr>
		<th><?php echo $form['modification_date_time']->renderLabel();?></th>
		<td>
		  <?php echo $form['modification_date_time']->renderError() ?>
		  <?php echo $form['modification_date_time'];?>
		</td>
	  </tr>
	  <tr>
		<th><?php echo $form['people_ref']->renderLabel();?></th>
		<td>
		  <?php echo $form['people_ref']->renderError() ?>
		  <?php echo $form['people_ref'];?>
		</td>
	  </tr>
	  <tr>
		<th><?php echo $form['description']->renderLabel();?></th>
		<td>
		  <?php echo $form['description']->renderError() ?>
		  <?php echo $form['description'];?>
		</td>
	  </tr>
	</tbody>
    <tfoot>
      <tr>
        <td colspan="2">
		  <?php echo $form['parts_ids'];?>
          <input id="submit" type="submit" value="<?php echo __('Add Maintenance');?>" />
        </td>
      </tr>
    </tfoot>
	</table>
<script  type="text/javascript">

$(document).ready(function () {
  $('form#collection_maintenance').submit(function () {
      $('form#collection_maintenance input[type=submit]').attr('disabled','disabled');
      //hideForRefresh($('form#collection_maintenance').parent());
      $.ajax({
          type: "POST",
          url: $(this).attr('action'),
          data: $(this).serialize(),
          success: function(html){
            $('form#collection_maintenance').before(html).remove();
          }
      });
      return false;
    });
});
</script>
</form>