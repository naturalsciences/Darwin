<?php

/**
 * Lithology filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LithologyFormFilter extends BaseLithologyFormFilter
{
  public function configure()
  {
    $this->useFields(array('name', 'level_ref'));
    $this->addPagerItems();
    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->widgetSchema['table'] = new sfWidgetFormInputHidden();
    $this->widgetSchema->setNameFormat('searchCatalogue[%s]');
    $this->widgetSchema['name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => 'getLevelsForLithology',
        'add_empty' => 'All'
      ));
    $this->widgetSchema->setLabels(array('level_ref' => 'Level'
                                        )
                                  );
    $this->validatorSchema['name'] = new sfValidatorString(array('required' => false,
                                                                 'trim' => true
                                                                )
                                                          );
    $this->validatorSchema['table'] = new sfValidatorString(array('required' => true));
  }

  public function doBuildQuery(array $values)
  {
    $query = parent::doBuildQuery($values);
    $this->addNamingColumnQuery($query, 'lithology', 'name_indexed', $values['name']);
    $query->andWhere("id != 0 ")
          ->limit($this->getCatalogueRecLimits());
    return $query;
  }
}
