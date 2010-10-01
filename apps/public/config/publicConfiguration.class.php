<?php

class publicConfiguration extends sfApplicationConfiguration
{
  protected $frontendRouting = null;

  public function generateFrontendUrl($name, $parameters = array())
  {
    return 'http://'.$_SERVER['SERVER_NAME'].$this->getFrontendRouting()->generate($name, $parameters);
  }

  public function getFrontendRouting()
  {
    if (!$this->frontendRouting)
    {
      $this->frontendRouting = new sfPatternRouting(new sfEventDispatcher());

      $config = new sfRoutingConfigHandler();
      $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/frontend/config/routing.yml'));
      $this->frontendRouting->setRoutes($routes);
    }

    return $this->frontendRouting;
  }
}
