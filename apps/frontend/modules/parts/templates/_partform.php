<tbody>
  <tr class="head_row">
	<th rowspan="2" class="rowspan"><?php echo __('Part');?></th>
	<th><?php echo $form['specimen_part']->renderLabel();?></th>
	<th><?php echo $form['category']->renderLabel();?></th>
	<th><?php echo $form['complete']->renderLabel();?></th>
	<th><?php echo $form['specimen_status']->renderLabel();?></th>
	<th><?php echo $form['specimen_part_count_min']->renderLabel();?></th>
	<th><?php echo $form['specimen_part_count_max']->renderLabel();?></th>
	<th rowspan="6" class="widget_row_delete rowspan">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?>
	  <?php $val = $form->getValue();
		if($val['id']):?>
		<?php echo link_to(image_tag('slide_right_enable.png'),'parts/details?id='.$val['id'], array('class'=>'part_detail_slide'));?>
	  <?php endif;?>
    </th>
  </tr>
  <tr>
	<td>
	  <?php echo $form['specimen_part'];?>
	</td>
	<td>
	  <?php echo $form['category'];?>
	</td>
	<td>
	  <?php echo $form['complete'];?>
	</td>
	<td>
	  <?php echo $form['specimen_status'];?>
	</td>
	<td>
	  <?php echo $form['specimen_part_count_min'];?>
	</td>
	<td>
	  <?php echo $form['specimen_part_count_max'];?>
	</td>
  </tr>




  <tr class="head_row">
	<th rowspan="2" class="rowspan"><?php echo __('Container');?></th>
	<th><?php echo $form['container_type']->renderLabel();?></th>
	<th><?php echo $form['container_storage']->renderLabel();?></th>
	<th><?php echo $form['sub_container_type']->renderLabel();?></th>
	<th><?php echo $form['sub_container_storage']->renderLabel();?></th>
	<th><?php echo $form['container']->renderLabel();?></th>
	<th><?php echo $form['sub_container']->renderLabel();?></th>
  </tr>
  <tr> 
	<td>
	  <?php echo $form['container_type'];?>
	</td>
	<td>
	  <?php echo $form['container_storage'];?>
	</td>
	<td>
	  <?php echo $form['sub_container_type'];?>
	</td>
	<td>
	  <?php echo $form['sub_container_storage'];?>
	</td>
	<td>
	  <?php echo $form['container'];?>
	</td>
	<td>
	  <?php echo $form['sub_container'];?>
	</td>
  </tr>



  <tr class="head_row">
	<th rowspan="2" class="rowspan"><?php echo __('Disposal');?></th>
	<th><?php echo $form['building']->renderLabel();?></th>
	<th><?php echo $form['floor']->renderLabel();?></th>
	<th><?php echo $form['room']->renderLabel();?></th>
	<th><?php echo $form['row']->renderLabel();?></th>
	<th><?php echo $form['shelf']->renderLabel();?></th>
	<th><?php echo $form['surnumerary']->renderLabel();?></th>
  </tr>
  <tr>
	<td>
	  <?php echo $form['building'];?>
	</td>
	<td>
	  <?php echo $form['floor'];?>
	</td>
	<td>
	  <?php echo $form['room'];?>
	</td>
	<td>
	  <?php echo $form['row'];?>
	</td>
	<td>
	  <?php echo $form['shelf'];?>
	</td>
	<td>
	  <?php echo $form['surnumerary'];?>
	</td>
  </tr>

</tbody>