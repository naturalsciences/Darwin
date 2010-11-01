<?php if(isset($view) && $view) $view= true ; else $view = false ; ?>
<?php use_stylesheet('encod.css') ?>
<?php $specimen_id = ($specimen->isNew())?'':$specimen->getId();?>
<?php $specimen_name = ($specimen->isNew())?'': sprintf(__('Specimen %d'),$specimen->getId());?>
<?php $individual_id = '';?>
<?php $individual_name = '';?>
<?php $part_tab_class = 'disabled';?>
<?php if(isset($individual)):?>
  <?php $individual_id = ($individual->isNew())?'':$individual->getId();?>
  <?php $individual_name = ($individual->isNew())?__('New Individual'):__('Individual ').$individual_id;?>
  <?php $part_tab_class = ($individual_id == '')?'disabled':'enabled';?>
<?php endif;?>
<div class="encoding">
	<div class="page">
		<div class="tabs<?php if($view) echo '_view' ; ?>">
			  <?php if($specimen->isNew()):?>

				<a class="enabled selected" id="tab_0"> &lt; <?php echo __('New Specimen');?> &gt; </a>
				<a class="disabled" id="tab_1"><?php echo __('Individuals overview');?></a>
				<a class="disabled" id="tab_2"><?php echo __('New Individual');?></a>
				<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
				<a class="disabled" id="tab_4"><?php echo __('New Part');?></a>

			  <?php elseif($mode == 'specimen_edit'):?>

				<a class="enabled selected" id="tab_0"> &lt; <?php echo $specimen_name;?> &gt; </a>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<?php echo link_to(__('New Individual'), 'individuals/edit?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
				<a class="disabled" id="tab_4"><?php echo __('New Part');?></a>

			  <?php elseif($mode == 'individuals_overview' ):?>
        <?php if($view) : ?>
  				<?php echo link_to($specimen_name, 'specimen/view?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>        
  				<a class="enabled selected" id="tab_1"> &lt; <?php echo __('Individuals overview');?> &gt; </a>
  				<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
         <?php else : ?>
  				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>  		  
				  <a class="enabled selected" id="tab_1"> &lt; <?php echo __('Individuals overview');?> &gt; </a>			
				  <?php echo link_to(__('New Individual'), 'individuals/edit?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				  <a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
				  <a class="disabled" id="tab_4"><?php echo __('New Part');?></a>
				<?php endif ; ?>

			  <?php elseif($mode == 'individual_edit'):?>

				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<a class="enabled selected" id="tab_2"> &lt; <?php echo $individual_name;?> &gt; </a>
				<?php echo link_to(__('Parts overview'), ($individual_id=='')?'individuals/edit?spec_id='.$specimen_id:'parts/overview?id='.$individual_id, array('id'=>'tab_3', 'class'=>$part_tab_class)); ?>
				<?php echo link_to(__('New Part'), ($individual_id=='')?'individuals/edit?spec_id='.$specimen_id:'parts/edit?indid='.$individual_id, array('id'=>'tab_4', 'class'=>$part_tab_class)); ?>

			  <?php elseif($mode == 'parts_overview'):?>

        <?php if($view) : ?>
  				<?php echo link_to($specimen_name, 'specimen/view?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>        
          <?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id.'&view=true', array('class'=>'enabled', 'id' => 'tab_1'));?>
				  <?php echo link_to($individual_name, 'individuals/view?id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				  <a class="enabled selected" id="tab_3"> &lt; <?php echo __('Parts overview');?> &gt; </a>
    		<?php else : ?>
				  <?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				  <?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				  <?php echo link_to($individual_name, 'individuals/edit?id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				  <a class="enabled selected" id="tab_3"> &lt; <?php echo __('Parts overview');?> &gt; </a>
    			<?php echo link_to(__('New Part'), 'parts/edit?indid='.$individual_id, array('class'=>'enabled', 'id' => 'tab_4'));?>
        <?php endif ; ?>
			  <?php else:?>

				<?php echo link_to($specimen_name, 'specimen/edit?id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_0'));?>
				<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen_id, array('class'=>'enabled', 'id' => 'tab_1'));?>
				<?php echo link_to($individual_name, 'individuals/edit?id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_2'));?>
				<?php echo link_to(__('Parts overview'), 'parts/overview?id='.$individual_id, array('class'=>'enabled', 'id' => 'tab_3'));?>
				<?php if ($sf_user->isAtLeast(Users::ENCODER)): ?>
				  <a class="enabled selected" id="tab_4">				
				     &lt; <?php if($part->isNew()):?>
					  <?php echo __('New Part'); ?>
				    <?php else:?>
					  <?php echo __('Edit Part'); ?>
				    <?php endif;?> &gt; 
				  </a>
        <?php endif ; ?>
			  <?php endif;?>
		  </div>
 		<div class="<?php if($view) echo 'panel_view' ; else echo 'panel edition ' ?> encod_screen" id="intro">
