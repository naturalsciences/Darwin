<?php slot('title', __('Edit Preferences widgets'));  ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<div class="page edition">
 <h1><?php echo __('My Preferences');?></h1>
  <?php echo form_tag('user/preferences');?>
    <table class="user_table">
      <thead>
        <tr>
          <th colspan="2"><?php echo __("Search in Specimens");?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>
          <?php echo $form['search_cols_specimen']->renderLabel();?>
          <div class="help_ico" alt="<?php echo $form['search_cols_specimen']->renderHelp();?>"></div>
          </th>
          <td><div class="search_cols_specimen" ><table><?php echo $form['search_cols_specimen'];?></table></div></td>
        </tr>
        <tr>
          <th>
          <?php echo $form['search_cols_individual']->renderLabel();?>
          <div class="help_ico" alt="<?php echo $form['search_cols_individual']->renderHelp();?>"></div>
          </th>
          <td><div class="search_cols_individual" ><table><?php echo $form['search_cols_individual'];?></table></div></td>
        </tr>
        <tr>
          <th>
          <?php echo $form['search_cols_part']->renderLabel();?>
          <div class="help_ico" alt="<?php echo $form['search_cols_part']->renderHelp();?>"></div>
          </th>
          <td><div class="search_cols_part" ><table><?php echo $form['search_cols_part'];?></table></div></td>
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