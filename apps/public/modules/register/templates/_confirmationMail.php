<?php if(isset($userParams)):?>
<?php $invitation = __('Dear')." ";
    if($userParams['physical'])
    {
      if (empty($userParams['title']))
      {
        $invitation .= $userParams['name'];
      }
      else
      {
        $invitation .= $userParams['title']." ".$userParams['name'];
      }
    }
    else
    {
      $invitation .= __('member of')." ".$userParams['name'];
    }
    $invitation .= ",\r\r";?>
<?php echo $invitation;?>
<?php echo __('Thank you for having registered on DaRWIN 2.')."\r";?>
<?php echo __('You can now log you in and enjoy enhanced services to our collections.')."\r";?>
<?php if(!empty($userParams['username']) && !empty($userParams['password']))
      {
        echo __('For your recall, here are your user name and password:')."\r\r";
        echo __('User name: ').$userParams['username']. "\r";
        echo __('Password: ').$userParams['password']. "\r\r";
      }?>
<?php echo __('To log you in, you can visit us on http://').$_SERVER['SERVER_NAME']." .\r\r";?>
<?php echo __('DaRWIN 2 team');?>
<?php endif;?>