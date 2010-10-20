<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_chronostratigraphy','eid'=> $form->getObject()->getId())); ?>
<?php slot('title', __('Edit Chronostratigraphic unit'));  ?>
<div class="page">
    <h1 class="edit_mode"><?php echo __('Edit Chronostratigraphic unit');?></h1>

    <?php include_partial('form', array('form' => $form)); ?>

    <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'cataloguewidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'chronostratigraphy')
	)); ?>

</div>
