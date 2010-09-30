<table>
  <tr>
    <td><?php echo $form['username'] ; ?></td>
    <td><?php echo $form['password'] ; ?></td>
    <td><input type="submit" name="login" value=">>" id="login_bt"></td>
    <td><a href="<?php echo url_for('register/login') ;?>" id="register"><?php echo __('Register') ; ?></a></td>
  </tr>
</table>
    
