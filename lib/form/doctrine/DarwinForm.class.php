<?php

class DarwinForm extends sfForm
{
  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }
}