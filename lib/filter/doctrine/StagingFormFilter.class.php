<?php

/**
 * Staging filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StagingFormFilter extends BaseStagingFormFilter
{
  public function configure()
  {
    $this->useFields(array());
    $levels = array(
      'specimens' => 'specimen',
      'individuals' => '&nbsp;&nbsp;individuals',
      'parts' => '&nbsp;&nbsp;&nbsp;&nbsp;parts',
      'tissue' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tissue',
      'dna' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dna',
    );
    $this->widgetSchema['slevel']  = new sfWidgetFormChoice(array('expanded'=>true,'choices'=> $levels));
    $this->validatorSchema['slevel'] = new sfValidatorChoice(array('choices' => array_keys($levels), 'required'=>false,'empty_value'=>'specimens'));
    $this->addPagerItems();
  }

  public function addSlevelColumnQuery(Doctrine_Query $query, $field, $value)
  {
     if ($value != "")
       $query->andWhere("level = ? ", $value);
     return $query;
  }
}
