<?php slot('title', __('Register'));  ?>
<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<div class="page">
  <h1><?php echo __('Register to DaRWIN 2');?></h1>
  <?php echo form_tag('register/index', array('id'=>'register_form'));?>
    <h2 class="title"><?php echo __("Register") ?></h2>
    <div class="borded">
      <table id="registration">
        <tbody>
          <tr>
            <td colspan="3">
              <?php echo $form->renderGlobalErrors() ?>
            </td>
          <tr>
          <tr>
            <th><?php echo $form['is_physical']->renderLabel();?>:</th>
            <td><?php echo $form['is_physical']->render();?></td>
            <td><?php echo $form['is_physical']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['sub_type']->renderLabel();?>:</th>
            <td><?php echo $form['sub_type']->render();?></td>
            <td><?php echo $form['sub_type']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['family_name']->renderLabel();?>:</th>
            <td><?php echo $form['family_name']->render();?></td>
            <td><?php echo $form['family_name']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['given_name']->renderLabel();?>:</th>
            <td><?php echo $form['given_name']->render();?></td>
            <td><?php echo $form['given_name']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['RegisterLoginInfosForm'][0]['user_name']->renderLabel();?>:</th>
            <td><?php echo $form['RegisterLoginInfosForm'][0]['user_name']->render();?></td>
            <td><?php echo $form['RegisterLoginInfosForm'][0]['user_name']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['RegisterLoginInfosForm'][0]['new_password']->renderLabel();?>:</th>
            <td><?php echo $form['RegisterLoginInfosForm'][0]['new_password']->render();?></td>
            <td><?php echo $form['RegisterLoginInfosForm'][0]['new_password']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['RegisterLoginInfosForm'][0]['confirm_password']->renderLabel();?>:</th>
            <td><?php echo $form['RegisterLoginInfosForm'][0]['confirm_password']->render();?></td>
            <td><?php echo $form['RegisterLoginInfosForm'][0]['confirm_password']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['RegisterCommForm'][0]['entry']->renderLabel();?>:</th>
            <td><?php echo $form['RegisterCommForm'][0]['entry']->render();?></td>
            <td><?php echo $form['RegisterCommForm'][0]['entry']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['RegisterLanguagesForm'][0]['language_country']->renderLabel();?>:</th>
            <td><?php echo $form['RegisterLanguagesForm'][0]['language_country']->render();?></td>
            <td><?php echo $form['RegisterLanguagesForm'][0]['language_country']->renderError();?></td>
          </tr>
          <tr>
            <th><?php echo $form['title']->renderLabel();?>:</th>
            <td><?php echo $form['title']->render();?></td>
            <td><?php echo $form['title']->renderError();?></td>
          </tr>
          <tr>
            <th></th>
            <td><?php echo $form['terms_of_use']->render();?>&nbsp;I accept the <?php echo link_to('terms of use', 'tof/index', array('id'=>'tof'));?></td>
            <td><?php echo $form['terms_of_use']->renderError();?></td>
          </tr>
          <tr>
            <th></th>
            <td colspan="2">
                <div class="check_right">
                  <?php echo $form->renderHiddenFields();?>
                  <input type="submit" name="submit" id="submit" value="<?php echo __('Register'); ?>">
                </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</div>
