<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  protected $publicRouting = null;

  public function generatePublicUrl($name, $parameters = array())
  {
    return 'http://'.$_SERVER['SERVER_NAME'].'/public.php'.$this->getPublicRouting()->generate($name, $parameters);
  }

  public function getPublicRouting()
  {
    if (!$this->publicRouting)
    {
      $this->publicRouting = new sfPatternRouting(new sfEventDispatcher());

      $config = new sfRoutingConfigHandler();
      $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/public/config/routing.yml'));
      $this->publicRouting->setRoutes($routes);
    }

    return $this->publicRouting;
  }
}
