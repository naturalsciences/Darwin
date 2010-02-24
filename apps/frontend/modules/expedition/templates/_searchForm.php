<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form id="expedition_filter" class="search_form" action="<?php echo url_for('expedition/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>
          <th><?php echo $form['name']->renderLabel() ?></th>
          <th><?php echo $form['expedition_from_date']->renderLabel(); ?></th>
          <th><?php echo $form['expedition_to_date']->renderLabel(); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['name']->render() ?></td>
          <td><?php echo $form['expedition_from_date']->render() ?></td>
          <td><?php echo $form['expedition_to_date']->render() ?></td>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content">
      </div>  
    </div>
    <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for('expedition/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form>
