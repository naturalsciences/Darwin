<?php if(isset($userParams)):?>
<?php
    if($userParams['physical'])
    {
      if (empty($userParams['title']))
      {
        echo __('Dear %name%,',array('%name%' => $userParams['name']));
      }
      else
      {
        echo __('Dear %name% %title%,',array('%name%' => $userParams['title'],'%title%'=> $userParams['name']));
      }
    }
    else
    {
      echo __('Dear member of %name%,',array('%name%' => $userParams['name']));
    }
    echo "\r\r";?>
<?php echo __('Thank you for having registered on DaRWIN 2.')."\r";?>
<?php echo __('You can now log in and enjoy our enhanced collection services.')."\r";?>
<?php if(!empty($userParams['username']))
      {
        echo __('For your reminder, here is your user name:')."\r\r";
        echo __('User name: %username%',array('%username%' => $userParams['username']))."\r\r";
      }?>
<?php echo __('To log you in, you can visit us on %address%',array('%address%' => $sf_request->getHost()))."\r\r";?>
<?php echo __('DaRWIN 2 team');?>
<?php endif;?>
