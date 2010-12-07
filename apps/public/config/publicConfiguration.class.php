<?php

class publicConfiguration extends sfApplicationConfiguration
{
  protected $backendRouting = null;

  public function generateBackendUrl($name, $parameters = array())
  {
    $env_str = '';
    switch($this->getEnvironment())
    {
      case 'prod': $env_str = '';break;
      case 'dev': $env_str = '_dev';break;
      case 'preprod': $env_str = '_pre';break;
    }
    return 'http://'.$_SERVER['SERVER_NAME'].'/backend'.$env_str.'.php'.$this->getBackendRouting()->generate($name, $parameters);
  }

  public function getBackendRouting()
  {
    if (!$this->backendRouting)
    {
      $this->backendRouting = new sfPatternRouting(new sfEventDispatcher());

      $config = new sfRoutingConfigHandler();
      $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/backend/config/routing.yml'));
      $this->backendRouting->setRoutes($routes);
    }

    return $this->backendRouting;
  }
}
