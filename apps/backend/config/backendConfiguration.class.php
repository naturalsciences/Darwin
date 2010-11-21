<?php

class backendConfiguration extends sfApplicationConfiguration
{
  protected $publicRouting = null;

  public function generatePublicUrl($name, $parameters = array())
  {
    $env_str = '';
    switch($this->getEnvironment())
    {
      case 'prod': $env_str = '';break;
      case 'dev': $env_str = '_dev';break;
//       case 'preprod': $env_str = '_pre';break;
    }
    return 'http://'.$_SERVER['SERVER_NAME'].'/public'.$env_str.'.php'.$this->getPublicRouting()->generate($name, $parameters);
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
