<?php if(isset($userParams)):?>
<?php
    if($userParams['physical'])
    {
      if (empty($userParams['title']))
      {
        echo __(sprintf('Dear %s,',$userParams['name']));
      }
      else
      {
        echo __(sprintf('Dear %s %s,',$userParams['title'], $userParams['name']));
      }
    }
    else
    {
      echo __(sprintf('Dear member of %s,',$userParams['name']));
    }
    echo "\r\r";?>
<?php echo __('Thank you for having registered on DaRWIN 2.')."\r";?>
<?php echo __('You can now log in and enjoy our enhanced collection services.')."\r";?>
<?php if(!empty($userParams['username']))
      {
        echo __('For your reminder, here is your user name:')."\r\r";
        echo __(sprintf('User name: %s',$userParams['username']))."\r\r";
      }?>
<?php echo __(sprintf('To log you in, you can visit us on %s','http://'.$_SERVER['SERVER_NAME']))."\r\r";?>
<?php echo __('DaRWIN 2 team');?>
<?php endif;?>