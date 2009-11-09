<div class="page">
  <h1><?php echo __('Expedition List');?></h1>
  <?php include_partial('searchForm', array('form' => $form)) ?>
  <a href="<?php echo url_for('expedition/new') ?>">New</a>
</div>
