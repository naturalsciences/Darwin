<?php slot('title', __('Edit Individuals'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual->getObject(), 'mode'=>'individual_edit'));?>

<table>
  <tbody>
    <tr>
      <td>
	<?php echo $individual['type']->render();?> - <?php echo $individual['sex']->render();?>
	<?php echo link_to(image_tag('slide_right_enable.png'),'parts/overview?id='.$individual->getObject()->getId(), array('class'=>'part_detail_slide'));?>
      </td>
    </tr>
  </tbody>
</table>

<?php include_partial('specimen/specAfterTab');?>

