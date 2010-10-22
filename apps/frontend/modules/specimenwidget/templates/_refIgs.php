<?php if(isset($view) && $view) : ?>
  <?php echo $form->getObject()->Igs->getIgNum() ; ?>
<?php else  : ?>
  <?php echo $form['ig_ref']->renderError() ?>
  <?php echo $form['ig_ref']->render() ?>
<?php endif ?>
