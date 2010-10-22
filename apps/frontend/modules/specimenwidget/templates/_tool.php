<?php if(isset($view) && $view) : ?>
  <ul class="tool">
   <?php foreach ($form as $tool) : ?>
     <?php echo ("<li>".$tool->getTool()."</li>") ; ?>
   <?php endforeach ; ?>
  </ul>
<?php else  : ?>
  <?php echo $form['coll_tools']->render();?>
  <?php echo $form['collecting_tools_list']->renderError();?>
  <?php echo $form['collecting_tools_list']->render();?>
<?php endif ; ?>
