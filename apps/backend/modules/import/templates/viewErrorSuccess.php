<?php slot('title', __('Errors occured during import'));  ?>

<div class="page" id="stats" >
  <h1><?php echo __('Imports');?></h1>
   <h2><?php echo __("List of errors occured during import") ; ?></h2>

    <ul class="board_news">
    <?php foreach($errors as $error) : ?>
      <li><?php echo $error ?></li>
    <?php endforeach ; ?>
    </ul>
    <hr />
    <p>  
      <a href="<?php echo url_for('import/index') ?>" class="bt_close"><?php echo __('Back');?></a>
    </p>

</div>

