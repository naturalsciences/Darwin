  <div class="blue_line">
    <table>
      <tr>
        <td>
        </td>
        <td class="blue_line_right">
          <?php echo form_tag('register/login', array('class'=>'register_form'));?>
            <table>
              <tr>
                <?php if(! $sf_user->isAuthenticated()):?>
                  <td class="field"><?php echo $form['username']->render(array('class'=>'small')); ?></td>
                  <td class="field"><?php echo $form['password']->render(array('class'=>'small')); ?></td>
                  <td id="login_bt"><?php echo $form['_csrf_token'] ; ?>
                    <input type="submit" value="&gt;&gt;">
                  </td>
                  <td class="menu_button"><?php echo link_to(__('Register'),'register/index') ;?></td>
                <?php else:?>
                  <td class="menu_button"><?php echo link_to(__('Go to Backend'),sfContext::getInstance()->getConfiguration()->generateBackendUrl('homepage')) ;?></td>
                  <td class="menu_button"><?php echo link_to(__('Logout'),'register/logout') ;?></td>
                <?php endif;?>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </div>
