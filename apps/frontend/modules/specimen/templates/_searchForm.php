<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form id="specimen_filter" class="search_form" action="<?php echo url_for('specimen/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>
          <th><?php echo $form['code']->renderLabel() ?></th>
          <th><?php echo $form['taxon_name']->renderLabel() ?></th>
          <th><?php echo $form['taxon_level']->renderLabel() ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['code']->render() ?></td>
          <td><?php echo $form['taxon_name']->render() ?></td>
          <td><?php echo $form['taxon_level']->render() ?></td>
          <td><?php echo $form->renderHiddenFields();?><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content">
      </div>  
    </div>
    <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for('specimen/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form>
