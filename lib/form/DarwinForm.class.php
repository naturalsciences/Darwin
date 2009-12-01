<?php

abstract class DarwinForm extends BaseForm
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