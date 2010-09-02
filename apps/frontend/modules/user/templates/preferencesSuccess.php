<?php slot('title', __('Edit Preferences widgets'));  ?>        
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>                                                                                              
<div class="page edition">
 <h1><?php echo __('My Preferences');?></h1>
  <form action="<?php url_for('user/preferences');?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

    <table class="user_table">
      <thead>
        <tr>
          <th colspan="2"><?php echo __("Search in Specimens");?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>
          <?php echo $form['spec_search_cols']->renderLabel();?>
          <div class="help_ico" alt="<?php echo $form['spec_search_cols']->renderHelp();?>"></div>
          </th>
          <td><div > <!--class="spec_search_cols_pref"--><table><?php echo $form['spec_search_cols'];?></table></div></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th colspan="2"><?php echo __("Board Widgets");?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>
            <?php echo $form['board_spec_rec_pp']->renderLabel();?>
            <div class="help_ico" alt="<?php echo $form['board_spec_rec_pp']->renderHelp();?>"></div>
          </th>
          <td><?php echo $form['board_spec_rec_pp'];?></td>
        </tr>
        <tr>
          <th>
            <?php echo $form['board_search_rec_pp']->renderLabel();?>
            <div class="help_ico" alt="<?php echo $form['board_search_rec_pp']->renderHelp();?>"></div>
          </th>
          <td><?php echo $form['board_search_rec_pp'];?></td>
        </tr>
      <tfoot>
        <tr>
          <td colspan='2'>
            <input type="submit" />
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>