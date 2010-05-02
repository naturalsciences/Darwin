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
      if(sfConfig::get('app_tracking_trackfields',null))
      {
	$conn->exec("SELECT set_config('darwin.track_fields', '".$user->getId()."', false);");
      }
    }
    $filterChain->execute();
  }
}