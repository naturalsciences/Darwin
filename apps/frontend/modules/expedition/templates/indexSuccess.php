<div class="page">
  <h1><?php echo __('Expedition List');?></h1>
  <?php include_partial('searchForm', array('form' => $form)) ?>
  <br /><br />
  <div class='new_link'>
    <a href="<?php echo url_for('expedition/new') ?>">New</a>
  </div>
</div>
