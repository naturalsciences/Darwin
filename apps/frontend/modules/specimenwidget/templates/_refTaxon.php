<?php if(isset($view) && $view) : ?>
  <?php echo $form->getObject()->Taxonomy->getName() ; ?>
<?php else  : ?>
  <?php echo $form['taxon_ref']->renderError() ?>
  <?php echo $form['taxon_ref']->render() ?>
<?php endif ; ?>
