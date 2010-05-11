<?php 
class trackingFilter extends sfFilter
{
  public function execute ($filterChain)
  {
    $user = $this->getContext()->getUser();

    if($user->isAuthenticated() && sfConfig::get('app_tracking_enabled',null))
    {
      $conn = Doctrine_Manager::connection();
      $conn->exec("SELECT set_config('darwin.userid', '".$user->getId()."', false);");
    }
    $filterChain->execute();
  }
}