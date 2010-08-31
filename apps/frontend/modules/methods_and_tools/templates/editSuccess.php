<?php if(isset($notion)):?>
  <?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_'.$notion,'eid'=> $form->getObject()->getId() )); ?>
  <?php slot('title', __('Edit '.$notion));  ?>
  <div class="page">
      <h1 class="edit_mode"><?php echo __('Edit '.$notion);?></h1>

      <?php include_partial('form', array('form' => $form, 'notion'=>$notion)) ?>

  <?php include_partial('widgets/screen', array(
    'widgets' => $widgets,
    'category' => 'cataloguewidget',
    'columns' => 1,
    'options' => array('eid' => $form->getObject()->getId(), 'table' => (($notion=='method')?'collecting_methods':'collecting_tools'))
    )); ?>
  </div>
<?php else:?>
  <?php echo __('Please define if you wish to edit tool or method');?>
<?php endif;?>