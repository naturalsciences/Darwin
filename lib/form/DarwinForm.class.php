<?php

abstract class DarwinForm extends sfForm
{
  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function setup()
  {
    sfWidgetFormSchema::setDefaultFormFormatterName('list');
  }
}