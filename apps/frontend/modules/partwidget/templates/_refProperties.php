<?php if($eid) : ?>
<?php echo get_component('cataloguewidget', 'properties', array('table' => 'SpecimenParts', 'eid' => $eid));?>
<?php else : ?>
<?php echo __('Please save your part in order to add properties') ?>
<?php endif; ?>
