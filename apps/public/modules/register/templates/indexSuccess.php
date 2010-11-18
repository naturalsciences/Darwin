<?php slot('title', __('Register'));  ?>
<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<div class="page">
  <h1><?php echo __("Register");?></h1>
  <?php echo form_tag('register/index', array('id'=>'registration_form'));?>
    <h2 class="title"><?php echo __("Please fill in the application form") ?></h2>
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
            <th><?php echo $form['RegisterLoginInfosForm'][0]['new_password']->renderLabel();?>
                <?php echo image_tag('info.png', array('class'=> 'passwd_info')) ; ?>:</th>
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
            <th><?php echo $form['captcha']->renderLabel();?>:</th>
            <td><?php echo $form['captcha']->render();?></td>
            <td><?php echo $form['captcha']->renderError();?></td>
          </tr>
          <tr>
            <th></th>
            <td><?php echo $form['terms_of_use']->render();?>&nbsp;I accept the <a href="#">terms of use</a></td>
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
<script language="javascript">
  $(document).ready(function () {
    $('form#registration_form').submit(function(){
      $('input#submit').attr('disabled', 'true');
      return true;
    });
    $(".passwd_info").each(function ()
    {
      $(this).qtip({
        show: { solo: true, when: { event: 'click' } },
        hide: { when: { event: 'click' } },// May be replaced by smth else
        style: {  name: "light", title: { padding: '3px'} },
        content: {
          title: {
            text: '&nbsp;',
            button: 'X'
          },
          text : '<p>Password must contain at least a case mix and at least one digit</p><hr><p>Password must be at least 6 characters length</p>',
          method: 'get'
        }
      });
    });    
  });
</script>
