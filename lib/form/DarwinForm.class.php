<?php

abstract class DarwinForm extends BaseForm
{
  protected static $recPerPages = array("1", "2", "5", "10", "25", "50", "75", "100");

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function setup()
  {
    sfWidgetFormSchema::setDefaultFormFormatterName('list');
  }
}