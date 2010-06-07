<?php if($eid) : ?>
<?php echo get_component('cataloguewidget', 'properties', array('table' => 'SpecimenIndividuals', 'eid' => $eid));?>
<?php else : ?>
<?php echo __('Please save your individual in order to add properties') ?>
<?php endif; ?>
