<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<?php $sFormClass = get_class($form->getEmbeddedForm('MassActionForm'));?>

<?php if(isset($mAction) && $mAction == 'collection_ref'):?>
  <?php include_partial('sub_collection_ref',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'taxon_ref'):?>
  <?php include_partial('sub_taxon_ref',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'lithology_ref'):?>
  <?php include_partial('sub_lithology_ref',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'lithostratigraphy_ref'):?>
  <?php include_partial('sub_lithostratigraphy_ref',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'mineralogy_ref'):?>
  <?php include_partial('sub_mineralogy_ref',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'chronostratigraphy_ref'):?>
  <?php include_partial('sub_chronostratigraphy_ref',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'station_visible'):?>
  <?php include_partial('sub_station_visible',array('form'=>$form, 'mAction' => $mAction));?>


<?php elseif(isset($mAction) && $mAction == 'maintenance'):?>
  <?php include_partial('sub_maintenance',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'floor'):?>
  <?php include_partial('sub_floor',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'room'):?>
  <?php include_partial('sub_room',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'row'):?>
  <?php include_partial('sub_row',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'shelf'):?>
  <?php include_partial('sub_shelf',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'container'):?>
  <?php include_partial('sub_container',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'sub_container'):?>
  <?php include_partial('sub_sub_container',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif(isset($mAction) && $mAction == 'building'):?>
  <?php include_partial('sub_building',array('form'=>$form, 'mAction' => $mAction));?>

<?php elseif($sFormClass == 'sfForm'):?>
  <?php echo $form['MassActionForm'];?>

<?php else:?>
  <div class="warning"><?php echo __("Houston, We've Got a Problem");?></div>
<?php endif;?>




