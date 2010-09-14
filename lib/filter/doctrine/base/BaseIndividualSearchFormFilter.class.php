<?php

/**
 * IndividualSearch filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseIndividualSearchFormFilter extends SpecimenSearchFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('individual_search_filters[%s]');
  }

  public function getModelName()
  {
    return 'IndividualSearch';
  }
}
