<table>
  <tr>
    <td class="field"><?php echo $form['username'] ; ?></td>
    <td class="field"><?php echo $form['password'] ; ?></td>
    <td id="login_bt"><?php echo $form['_csrf_token'] ; ?>
      <a href="#">&gt;&gt;</a>
      <script>
        $('#login_bt a').click(function(){
          $(this).closest('form').submit() ;
        });       
      </script>
    </td>
    <td id="register"><a href="<?php echo url_for('register/index') ;?>"><?php echo __('Register') ; ?></a></td>
  </tr>
</table>