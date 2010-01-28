<?php

/**
 * Institutions filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InstitutionsFormFilter extends BaseInstitutionsFormFilter
{
  public function configure()
  {
    $this->useFields(array('family_name','is_physical'));

    $this->addPagerItems();
    $this->widgetSchema['is_physical'] = new sfWidgetFormInputHidden();
    $this->setDefault('is_physical', 0); 

    $this->widgetSchema['family_name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
  }

  public function addFamilyNameColumnQuery($query, $field, $val)
  {
    $query->andWhere("formated_name_ts  @@ search_words_to_query('people' , 'formated_name_ts', ? , 'contains') ", $val['text']);
    return $query;
  }

}
