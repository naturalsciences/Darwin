<?php

/**
 * IndividualSearch form base class.
 *
 * @method IndividualSearch getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseIndividualSearchForm extends SpecimenSearchForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('individual_search[%s]');
  }

  public function getModelName()
  {
    return 'IndividualSearch';
  }

}
