<?php if(isset($view) && $view) : ?>
  <ul class="tool">
   <?php foreach ($form as $method) : ?>
     <?php echo ("<li>".$method->getMethod()."</li>") ; ?>
   <?php endforeach ; ?>
  </ul>
<?php else  : ?>
  <?php echo $form['coll_methods']->render();?>
  <?php echo $form['collecting_methods_list']->renderError();?>
  <?php echo $form['collecting_methods_list']->render(); ?>
<?php endif ; ?>
