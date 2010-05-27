<?php slot('title', __('Edit Individuals'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'mode'=>'individuals_overview'));?>

<table>
  <thead style="<?php echo (count($individuals))?'':'display: none;';?>">
    <tr>
    </tr>
  </thead>
  <tbody>
    <?php foreach($individuals as $i => $individual):?>
      <tr>
	<td>
	  <?php echo $individual->getType();?> - <?php echo $individual->getSex();?>
	  <?php echo link_to(image_tag('slide_right_enable.png'),'parts/edit?id='.$individual->getId(), array('class'=>'part_detail_slide'));?>
	</td>
      </tr>
    <?php endforeach;?>
  </tbody>
</table>

<?php include_partial('specimen/specAfterTab');?>