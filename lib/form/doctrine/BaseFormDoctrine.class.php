<?php

/**
 * Project form base class.
 *
 * @package    form
 * @version    SVN: $Id: sfDoctrineFormBaseTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine
{
  public function setup()
  {
    sfWidgetFormSchema::setDefaultFormFormatterName('list');
  }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function getCurrentCulture()
  {
    return sfContext::getInstance()->getUser()->getCulture();
  }

  public function getJavascripts()
  {
    $javascripts=parent::getJavascripts();
    $javascripts[]='/js/qtiped_forms.js';
    return $javascripts;
  }
}