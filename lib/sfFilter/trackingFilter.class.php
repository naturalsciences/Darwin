<?php 
class trackingFilter extends sfFilter
{
  public function execute ($filterChain)
  {
    if ($this->isFirstCall())
    {
      $user = $this->getContext()->getUser();

      if($user->isAuthenticated())
      {

        //Check if the user still exists
        $q = Doctrine_Query::create()
          ->useResultCache(new Doctrine_Cache_Apc())
          ->setResultCacheLifeSpan(60 * 5) //5 minutes
          ->from('Users')
          ->andWhere('id = ?',$user->getId());
        $usr=$q->fetchOne();

        if(!$usr)
        {
          $user->clearCredentials();
          $user->setAuthenticated(false);

          return $this->getContext()->getController()->redirect($this->getContext()->getConfiguration()->generatePublicUrl('homepage'));
        }
        $time = date('Y-m-d H:i:s');
        if($time - $user->getAttribute('last_seen',0) > 20)
        { 
          $usr->setLastSeen($time);
          $usr->save();
          $user->setAttribute('last_seen', $time);
        }

        $user->setAttribute('db_user_type', $usr->getDbUserType());
      }
      if($user->isAuthenticated() && sfConfig::get('app_tracking_enabled',null))
      {
        $conn = Doctrine_Manager::connection();
        $conn->exec("SELECT set_config('darwin.userid', '".$user->getId()."', false);");
      }
    }

    $filterChain->execute();
  }
}