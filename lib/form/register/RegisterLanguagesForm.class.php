<?php

/**
 * Register Languages form.
 *
 * @package    form
 * @subpackage RegisterLanguages
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class RegisterLanguagesForm extends UsersLanguagesForm
{
  public function configure()
  {
    parent::configure();
    unset($this['mother'],$this['preferred_language']);
  }
}