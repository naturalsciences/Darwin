<table>
  <tr>
	<th><?php echo $form['specimen_part_count_min']->renderLabel();?></th>
	<td>
	  <?php echo $form['specimen_part_count_min']->renderError();?>
	  <?php echo $form['specimen_part_count_min']->render() ?>
	</td>
  </tr>
  <tr>
	<th><?php echo $form['specimen_part_count_max']->renderLabel();?></th>
	<td>
	  <?php echo $form['specimen_part_count_max']->renderError();?>
	  <?php echo $form['specimen_part_count_max']->render() ?>
	</td>
  </tr>
</table>