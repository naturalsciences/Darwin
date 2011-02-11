<?php

/**
 * Expeditions filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ExpeditionsIgsFormFilter extends SpecimenSearchFormFilter
{
  public function configure()
  {
    $this->useFields(array('ig_num', 'expedition_name'));
    $this->addPagerItems();
    $this->widgetSchema['ig_num'] = new sfWidgetFormInputText();
    $this->widgetSchema['expedition_name'] = new sfWidgetFormInputText();
    $this->widgetSchema->setNameFormat('searchExpeditionIgs[%s]');


    $this->validatorSchema['ig_num'] = new sfValidatorString(array('required' => false, 'trim' => true));
    $this->validatorSchema['expedition_name'] = new sfValidatorString(array('required' => false, 'trim' => true));
  }

  public function doBuildQuery(array $values)
  {
    $query = DQ::create()
      ->select('DISTINCT ig_num, expedition_name, ig_ref, expedition_ref')
      ->from('IgsSearch i')
      ->orderBy('ig_num, expedition_name')
      ->andWhere('expedition_name != \'\'') ;
    if($values['ig_num'] != '') $query->andWhere('ig_num = ?', $values['ig_num']) ;
    else $query->andWhere('ig_num != \'\'') ;
    $this->addNamingColumnQuery($query, 'expeditions', 'name_ts', $values['expedition_name'],null,'expedition_name_ts');
    
    return $query;
  }
  
}
