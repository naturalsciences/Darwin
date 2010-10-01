<ul>
    <li><?php echo $form['username'] ; ?></li>
    <li><?php echo $form['password'] ; ?></li>
    <li id="login_bt"><?php echo $form['_csrf_token'] ; ?>
      <a href="#">>></a>
      <script>
        $('#login_bt a').click(function(){
          $(this).closest('form').submit() ;
        });       
      </script>
    </li>
    <li id="register"><a href="<?php echo url_for('register/index') ;?>"><?php echo __('Register') ; ?></a></li>
</ul>
