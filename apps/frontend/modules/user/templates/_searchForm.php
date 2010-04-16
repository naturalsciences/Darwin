<?php include_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?> 

<form id="users_filter" class="search_form" method="post" action="<?php echo url_for('user/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>">
  <div class="container">
    <?php echo $form['screen']->render() ?>
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>
          <th><?php echo $form['family_name']->renderLabel('Name') ?></th>
          <th><?php echo $form['db_user_type']->renderLabel('Type') ?></th>
          <th><?php echo $form['is_physical']->renderLabel('Status') ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['family_name']->render() ?></td>
          <td><?php echo $form['db_user_type']->render() ?></td>
          <td><?php echo $form['is_physical']->render() ?></td>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content"> 
      </div>
    </div> 
  </div>
</form> 
