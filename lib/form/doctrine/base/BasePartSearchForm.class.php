<?php

/**
 * PartSearch form base class.
 *
 * @method PartSearch getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePartSearchForm extends IndividualSearchForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('part_search[%s]');
  }

  public function getModelName()
  {
    return 'PartSearch';
  }

}
