<?php slot('title', __('Edit Individuals'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> new SpecimenIndividuals()));?>

<h1>Hello World</h1>

<ul>
<?php foreach($individuals as $i => $individual):?>
  <li>
	  <?php echo $individual->getType();?> - <?php echo $individual->getSex();?>
	  <?php echo link_to(image_tag('slide_right_enable.png'),'parts/edit?id='.$individual->getId(), array('class'=>'part_detail_slide'));?>
  </li>
<?php endforeach;?>
</ul>

<?php include_partial('specimen/specAfterTab');?>

