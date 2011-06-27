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
    $this->addPagerItems();
    $model = new DisplayImportDna();
    $levels = $model->getLevels();
    $this->widgetSchema['slevel']  = new sfWidgetFormChoice(array('expanded'=>true,'choices'=> $levels));
    $this->validatorSchema['slevel'] = new sfValidatorChoice(array('choices' => array_keys($levels), 'required'=>false,'empty_value'=>'specimens'));
    $this->widgetSchema->setLabels(array(
      'slevel'=>'Levels',
    ) );
  }

  public function addSlevelColumnQuery(Doctrine_Query $query, $field, $value)
  {
     if ($value != "")
       $query->andWhere("level = ? ", $value);
     return $query;
  }
}
