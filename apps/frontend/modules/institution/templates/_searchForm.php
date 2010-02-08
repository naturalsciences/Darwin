<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form id="institution_filter" class="search_form" method="post" action="<?php echo url_for('institution/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>">
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>  
          <th><?php echo $form['family_name']->renderLabel('Name');?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['family_name'];?><?php echo $form['is_physical'];?></td>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content"> 
      </div>
    </div>
    <div class='new_link'><a href="<?php echo url_for('institution/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form> 