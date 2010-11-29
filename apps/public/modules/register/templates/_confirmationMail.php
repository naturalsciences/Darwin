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
<?php if(!empty($userParams['username']))
      {
        echo __('For your recall, here is your user name:')."\r\r";
        echo __('User name: ').$userParams['username']. "\r\r";
      }?>
<?php echo __('To log you in, you can visit us on http://').$_SERVER['SERVER_NAME']." .\r\r";?>
<?php echo __('DaRWIN 2 team');?>
<?php endif;?>