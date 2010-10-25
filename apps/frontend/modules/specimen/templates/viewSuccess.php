<?php slot('title', __('View Specimen'));  ?>
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen','eid'=> $specimen->getSpecRef(),'view' => true)); ?>
<?php use_stylesheet('widgets.css') ?>
<?php use_javascript('widgets.js') ?>
<?php use_javascript('catalogue.js') ?>
<?php use_javascript('button_ref.js') ?>
<div class="page">
  <div class="tabs_view">
    <a class="enabled selected" id="tab_0"> &lt; <?php echo sprintf(__('Specimen %d'),$specimen->getSpecRef());?> &gt; </a>
		<?php echo link_to(__('Individuals overview'), 'individuals/overview?spec_id='.$specimen->getSpecRef()."&view=true", array('class'=>'enabled', 'id' => 'tab_1'));?>
		<?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>
			<?php echo link_to(__('New Individual'), 'individuals/edit?spec_id='.$specimen->getSpecRef(), array('class'=>'enabled', 'id' => 'tab_2'));?>
			<a class="disabled" id="tab_3"><?php echo __('Parts overview');?></a>
			<a class="disabled" id="tab_4"><?php echo __('New Part');?></a>		
		<?php endif ; ?>
  </div>

  <div class="panel_view encod_screen edition" id="intro">
   <div>	
      <?php include_partial('widgets/screen', array(
        'widgets' => $widgets,
        'category' => 'specimenwidgetview',	
        'columns' => 2,	
        'options' => array('eid'=> $specimen->getSpecRef(), 'level' => 2, 'view' => true),
      )); ?>
    </div>
    
    <p class="clear"></p>
    <p align="right">
      <?php if ($sf_user->isAtLeast(Users::ENCODER)): ?>
        <?php echo link_to(__('New specimen'), 'specimen/new', array('class' => 'bt_close')) ?>
        &nbsp;<a href="<?php echo url_for('specimen/new?duplicate_id='.$specimen->getSpecRef());?>" class="duplicate_link bt_close"><?php echo __('Duplicate specimen');?></a>
      <?php endif?>
      &nbsp;<a class="bt_close" href="<?php echo url_for('specimensearch/index') ?>" id="spec_cancel"><?php echo __('Back');?></a>
    </p>
  </div>
</div>
