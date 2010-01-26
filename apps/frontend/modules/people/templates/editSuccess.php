<?php slot('title', __('Edit People'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'people','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
                                                                                               
<div class="page">
  <h1 class="edit_mode">Edit People</h1>
  <?php include_partial('form', array('form' => $form)) ?>


<script type="text/javascript">
var chgstatus_url='<?php echo url_for('widgets/changeStatus?category=people');?>';
var chgorder_url='<?php echo url_for('widgets/changeOrder?category=people');?>';
var reload_url='<?php echo url_for('widgets/reloadContent?category=people&eid='.$form->getObject()->getId());?>';
</script>
 <ul class="board_col one_col encod_screen">
<?php foreach($widgets as $id => $widget):?>
  <?php if(!$widget->getVisible()) continue;?>
  <?php include_partial('widgets/wlayout', array(
	'widget' => $widget->getGroupName(),
	'is_opened' => $widget->getOpened(),
	'category' => 'peoplewidget',
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'people')
	)); ?>
<?php endforeach;?>
</ul>

</div>