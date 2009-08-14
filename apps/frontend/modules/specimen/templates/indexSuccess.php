<?php slot('title', __('Add Specimens'));  ?>
<?php use_helper('Javascript') ?>
<?php echo javascript_tag("
var chgstatus_url='".url_for('widgets/changeStatus?category=specimen')."';
var chgorder_url='".url_for('widgets/changeOrder?category=specimen')."';
var reload_url='".url_for('widgets/reloadContent?category=specimen')."';
");?>
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen')) ?>

<div class="encoding">
    <?php echo image_tag('encod_left_disable.png','id="arrow_left" class="scrollButtons left"');?>
	<div class="page">
			<ul class="tabs">
				<li class="enabled selected" id="tab_0"> &lt; New Specimen &gt; </li>
				<li class="disabled" id="tab_1">Individuals</li>
				<li class="disabled" id="tab_2">Properties</li>
			</ul>
		<div class="tab_box" id="slider">
			<div class="scroll">
				<!-- the element that will be scrolled during the effect -->
				<div class="scrollContainer">
					<div class="panel" id="intro">
					<form action="<?php echo url_for('specimen/submit') ?>" method="POST">
					
					
  <ul class="board_col">
    <?php $changed_col=false;?>
    <?php foreach($widgets as $id => $widget):?>
      <?php if(!$widget->getVisible()) continue;?>

      <?php if($changed_col==false && $widget->getColNum()==2):?>
	    <?php $changed_col=true;?>
	      </ul>
	      <div class="board_spacer">&nbsp;</div>
	      <ul class="board_col">
      <?php endif;?>
	  <?php include_partial('widgets/wlayout', array(
            'widget' => $widget->getGroupName(),
            'is_opened' => $widget->getOpened(),
            'category' => 'specimenwidget')
	  ); ?>
    <?php endforeach;?>
    <?php if($changed_col==false):?>
      </ul>
      <div class="board_spacer">&nbsp;</div>
      <ul class="board_col">
      <?php endif;?>
  </ul>
  <input type="submit" value="Submit" />
                    </div>
                    <div class="panel"> <a href="#" onclick="$('#submit').trigger('click');return false;">Click here</a></div>
					<div class="panel"> How it Works </div>
				</div>
			</div>
		</div>
	</div>
	<?php echo image_tag('encod_right_disable.png','id="arrow_right" class="scrollButtons right"');?>
</div>