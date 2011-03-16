<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // for compatibility / remove and enable only the plugins you want
    $this->enableAllPluginsExcept(array('sfPropelPlugin'));  
  }

 /**
  * Configure the Doctrine engine
  **/
  public function configureDoctrine(Doctrine_Manager $manager)
  {
   $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, new Doctrine_Cache_Apc());
  }
}
