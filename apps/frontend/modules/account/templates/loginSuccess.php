<div class="login">
    <?php echo __('This application requires an authentication.
    Please Log in with your username and password.');?>
    <form action="<?php echo url_for('account/login') ?>" method="post">
        <ul class="form">
            <?php echo $form ?>
            <li><input type="submit" value="<?php echo __('Log in');?>" /></li>
        </ul>
    </form>
</div>
