<?php

/**
 * Taxonomy filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SpecimensFormFilter extends BaseSpecimensFormFilter
{
  public function configure()
  {
    $this->addPagerItems();
    $this->widgetSchema->setNameFormat('searchSpecimen[%s]');
    $this->widgetSchema['caller_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['code'] = new sfWidgetFormInputText();
    $this->widgetSchema['taxon_name'] = new sfWidgetFormInputText(array(), array('class'=>'medium_size'));
    $this->widgetSchema['taxon_level'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes','parameters'=>array(array('table'=>'taxonomy'))),
        'add_empty' => 'All'
      ));

    $this->widgetSchema->setLabels(array('code' => 'Specimen code(s)',
                                         'taxon_name' => 'Taxon',
                                         'taxon_level' => 'Level'
                                        )
                                  );

    $this->validatorSchema['code'] = new sfValidatorString(array('required' => false,
                                                                 'trim' => true
                                                                )
                                                          );
    $this->validatorSchema['taxon_name'] = new sfValidatorString(array('required' => false,
                                                                       'trim' => true
                                                                      )
                                                                );
    $this->validatorSchema['taxon_level'] = new sfValidatorString(array('required' => false));
    $this->validatorSchema['caller_id'] = new sfValidatorString(array('required' => false));
  }

  public function addCodeColumnQuery(Doctrine_Query $query, $field, $values)
  {
    $alias = $query->getRootAlias();
    $query->leftJoin($alias.'.SpecimensCodes cod')
          ->andWhere("referenced_relation = ?", array('specimens'));
    if ($values != "")
    {
      $this->addNamingColumnQuery($query, 'codes', 'full_code_indexed', $values , 'cod');
    }
    return $query;
  }

  public function addTaxonNameColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if ($values != "")
    {
      $this->addNamingColumnQuery($query, 'taxonomy', 'name_indexed', $values, 't');
    }
    return $query;
  }

  public function addTaxonLevelColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if ($values != "")
    {
      $query->addWhere('level_ref = ?', $values);
    }
    return $query;
  }

  public function doBuildQuery(array $values)
  {
    $query = parent::doBuildQuery($values);
    $alias = $query->getRootAlias();
    $query->innerJoin($alias.'.Taxonomy t')
          ->innerJoin('t.Level cl')
          ->andWhere("t.id != 0 ")
          ->limit($this->getCatalogueRecLimits());
    return $query;
  }
}
