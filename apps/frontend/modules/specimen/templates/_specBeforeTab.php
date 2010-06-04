<?php use_stylesheet('encod.css') ?>
<?php $specimen_id = ($specimen->isNew())?'':$specimen->getId();?>
<?php $specimen_name = ($specimen->isNew())?'':$specimen->getName();?>
<?php $individual_id = '';?>
<?php $individual_name = '';?>
<?php $part_tab_class = 'disabled';?>
<?php if(isset($individual)):?>
  <?php $individual_id = ($individual->isNew())?'':$individual->getId();?>
  <?php $individual_name = ($individual->isNew())?__('New Individual'):__('Individual ').$individual_id;?>
  <?php $part_tab_class = ($individual_id == '')?'disabled':'enabled';?>
<?php endif;?>
<div class="encoding">
	<?php if ($specimen->isNew() || $mode == 'specimen_edit'):?>
	  <?php echo image_tag('encod_left_disable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"');?>
	<?php elseif($mode == 'individuals_overview'):?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'specimen/edit?id='.$specimen_id);?>
	<?php elseif($mode == 'individual_edit'):?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'individuals/overview?spec_id='.$specimen_id);?>
	<?php elseif($mode == 'parts_overview'):?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'individuals/edit?spec_id='.$specimen_id.'&individual_id='.$individual_id);?>
	<?php else:?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'parts/overview?id='.$individual_id);?>
	<?php endif;?> 

	<div class="page">
		<div class="tabs">
			  <?php if($specimen->isNew()):?>

				<a class="enabled selected" id="tab_0"> &lt; <?php echo __('New Specimen');?> &gt; </a>
				<a class="disabled" id="tab_1"><?php echo __('Individuals overview');?></a>
				<a class="disabled" id="tab_2"><?php echo __('New Individual');?></a>
				<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
				<a class="disabled" id="tab_4"><?php echo __('New Part');?></a>

			  <?php elseif($mode == 'specimen_edit'):?>

				<a class="enabled selected" id="tab_0"><?php echo $specimen_name;?></a>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<?php echo link_to(__('New Individual'), 'individuals/edit?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
				<a class="disabled" id="tab_4"><?php echo __('New Part');?></a>

			  <?php elseif($mode == 'individuals_overview' ):?>

				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				<a class="enabled selected" id="tab_1"> &lt; <?php echo __('Individuals overview');?> &gt; </a>
				<?php echo link_to(__('New Individual'), 'individuals/edit?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
				<a class="disabled" id="tab_4"><?php echo __('New Part');?></a>

			  <?php elseif($mode == 'individual_edit'):?>

				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<a class="enabled selected" id="tab_2"> &lt; <?php echo $individual_name;?> &gt; </a>
				<?php echo link_to(__('Parts overview'), ($individual_id=='')?'individuals/edit?spec_id='.$specimen_id:'parts/overview?id='.$individual_id, array('id'=>'tab_3', 'class'=>$part_tab_class)); ?>
				<?php echo link_to(__('New Part'), ($individual_id=='')?'individuals/edit?spec_id='.$specimen_id:'parts/edit?indid='.$individual_id, array('id'=>'tab_4', 'class'=>$part_tab_class)); ?>

			  <?php elseif($mode == 'parts_overview'):?>

				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<?php echo link_to($individual_name, 'individuals/edit?spec_id='.$specimen_id.'&individual_id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				<a class="enabled selected" id="tab_3"> &lt; <?php echo __('Parts overview');?> &gt; </a>
				<?php echo link_to(__('New Part'), 'parts/edit?indid='.$individual_id, array('class'=>'enabled', 'id' => 'tab_4'));?>
			  <?php else:?>

				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<?php echo link_to($individual_name, 'individuals/edit?spec_id='.$specimen_id.'&individual_id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				<?php echo link_to(__('Parts overview'), 'parts/overview?id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_3'));?>
				<a class="enabled selected" id="tab_4">
				   &lt; <?php if($part->isNew()):?>
					<?php echo __('New Part'); ?>
				  <?php else:?>
					<?php echo __('Edit Part'); ?>
				  <?php endif;?> &gt; 
				</a>

			  <?php endif;?>
		  </div>
 		<div class="panel encod_screen edition" id="intro">