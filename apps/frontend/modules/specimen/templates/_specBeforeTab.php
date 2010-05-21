<?php use_stylesheet('encod.css') ?>
<div class="encoding">
	<?php if ($specimen->isNew()):?>
	  <?php echo image_tag('encod_left_disable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"');?>
	<?php elseif(! isset($individual) ):?>
	  <?php echo image_tag('encod_left_disable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"');?>
	<?php elseif($individual->isNew() ):?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'specimen/edit?id='.$specimen->getId());?>
	<?php elseif($part->isNew()):?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'individuals/edit?id='.$specimen->getId());?>
	<?php else:?>
	  <?php echo link_to(image_tag('encod_left_enable.png','id="arrow_left" alt="'.__("Go Previous").'" class="scrollButtons left"'),'parts/edit?id='.$individual->getId());?>
	<?php endif;?> 

	<div class="page">
		<ul class="tabs">
			  <?php if($specimen->isNew()):?>

				<li class="enabled selected" id="tab_0"> &lt; <?php echo __('New Specimen');?> &gt; </li>
				<li class="disabled" id="tab_1"><?php echo __('Individuals');?></li>
				<li class="disabled" id="tab_1"><?php echo __('Parts');?></li>
				<li class="disabled" id="tab_3"><?php echo __('Parts Details');?></li>

			  <?php elseif(! isset($individual) ):?>

				<li class="enabled selected" id="tab_0"><?php echo $specimen->getName();?></li>
				<li class="enabled" id="tab_1"><?php echo link_to(__('Individuals'), 'individuals/edit?id='.$specimen->getId());?></li>
				<li class="disabled" id="tab_2"><?php echo __('Parts');?></li>
				<li class="disabled" id="tab_3"><?php echo __('Parts Details');?></li>

			  <?php elseif($individual->isNew() ):?>

				<li class="enabled" id="tab_0"><?php echo link_to($specimen->getName(), 'specimen/edit?id='.$specimen->getId());?></li>
				<li class="enabled selected" id="tab_1"> &lt; <?php echo __('Individuals');?> &gt; </li>
				<li class="disabled" id="tab_2"><?php echo __('Parts');?></li>
				<li class="disabled" id="tab_3"><?php echo __('Parts Details');?></li>

			  <?php elseif($part->isNew()):?>

				<li class="enabled" id="tab_0"><?php echo link_to($specimen->getName(), 'specimen/edit?id='.$specimen->getId());?></li>
				<li class="enabled" id="tab_1"><?php echo link_to(__('Individuals'), 'individuals/edit?id='.$specimen->getId());?></li>
				<li class="enabled selected" id="tab_2"><?php echo __('Parts');?></li>
				<li class="disabled" id="tab_3"><?php echo __('Parts Details');?></li>

			  <?php else:?>

				<li class="enabled" id="tab_0"><?php echo link_to($specimen->getName(), 'specimen/edit?id='.$specimen->getId());?></li>
				<li class="enabled" id="tab_1"><?php echo link_to(__('Individuals'), 'individuals/edit?id='.$specimen->getId());?></li>
				<li class="enabled" id="tab_2"><?php echo link_to(__('Parts'),'parts/edit?id='.$individual->getId());?></li>
				<li class="enabled selected" id="tab_3"><?php echo __('Parts Details');?></li>
			  <?php endif;?>
		</ul>
 		<div class="panel encod_screen edition" id="intro">