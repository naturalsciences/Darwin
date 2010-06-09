<?php slot('title', __('Edit Maintenance'));  ?>
<div class="page" id="maintenance">
  <h1><?php echo __('Edit Maintenance :');?></h1>

    <div class="action_maintenance">
	<?php include_partial('form', array('form' => $form) );?>
	</div>