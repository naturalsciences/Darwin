<?php slot('widget_title',__('Expedition'));  ?>
<?php slot('widget_mandatory_refExpedition',false);  ?>

<?php echo $form['expedition_ref']->renderError() ?>
<?php echo $form['expedition_ref']->render() ?>