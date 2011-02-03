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
      ->select('DISTINCT i.ig_num, e.name, ig_ref, expedition_ref')
      ->from('SpecimenSearch s')
      ->leftJoin('s.Expedition e')
      ->leftJoin('s.Ig i')
      ->orderBy('ig_num, name') 
      ->andWhere('i.ig_num != \'\'')
      ->andWhere('e.name != \'\'') ;
    $this->addNamingColumnQuery($query, 'ig_num', 'indexed', $values['ig_num'],null,'ig_num_indexed');
    $this->addNamingColumnQuery($query, 'expedition_name', 'indexed', $values['expedition_name'],null,'expedition_name_indexed');    
    return $query;
  }
  
}
