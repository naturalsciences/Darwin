<table>
  <tr>
    <td class="field"><?php echo $form['username'] ; ?></td>
    <td class="field"><?php echo $form['password'] ; ?></td>
    <td id="login_bt"><?php echo $form['_csrf_token'] ; ?>
      <input type="submit" value="&gt;&gt;">
    </td>
    <td id="register"><a href="<?php echo url_for('register/index') ;?>"><?php echo __('Register') ; ?></a></td>
  </tr>
</table>