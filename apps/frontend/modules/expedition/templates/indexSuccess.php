<div class="page">
  <h1><?php echo __('Expedition List');?></h1>
  <?php include_partial('searchForm', array('form' => $form)) ?>
  <br />
  <?php if(isset($expeditions)):?>
    <?php include_partial('searchResults', array('expeditions' => $expeditions, 'is_choose' => false)) ?>
  <?php endif;?>
  <br />
  <a href="<?php echo url_for('expedition/new') ?>">New</a>
</div>
