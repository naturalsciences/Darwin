<?php slot('widget_title',__('Collection'));  ?>
<?php slot('widget_mandatory_refCollection',true);  ?>

<?php echo $form['collection_ref']->renderError() ?>
<?php echo $form['collection_ref']->render() ?>