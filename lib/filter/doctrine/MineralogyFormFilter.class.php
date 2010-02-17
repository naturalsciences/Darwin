<?php

/**
 * Mineralogy filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MineralogyFormFilter extends BaseMineralogyFormFilter
{
  public function configure()
  {
    $this->useFields(array('code', 'name', 'classification', 'level_ref'));
    $this->addPagerItems();

    $this->widgetSchema['code'] = new widgetFormInputChecked(array('model' => 'Mineralogy',
                                                                   'method' => 'getCode',
                                                                   'nullable' => true,
                                                                   'link_url' => 'mineralogy/searchFor',
                                                                   'notExistingAddDisplay' => false,
                                                                   'autocomplete_minChars' => 2,
                                                                   'behindScene' => false
                                                                  )
                                                            );
    $this->widgetSchema['name'] = new sfWidgetFormInputText();

    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => 'getLevelsForMineralogy',
        'add_empty' => 'All'
      ));

    $classificationKeys = array('strunz'=>'Strunz', 'dana'=>'Dana');
    $this->widgetSchema['classification'] = new sfWidgetFormChoice(array(
        'choices'  => $classificationKeys,
    ));
    $this->widgetSchema['table'] = new sfWidgetFormInputHidden();

    $this->widgetSchema->setNameFormat('searchCatalogue[%s]');

    $this->widgetSchema['code']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['name']->setAttributes(array('class'=>'medium_size'));

    $this->widgetSchema->setLabels(array('classification' => $this->getI18N()->__('Class.'),
                                         'level_ref' => $this->getI18N()->__('Level')
                                        )
                                  );

    $this->validatorSchema['code'] = new sfValidatorString(array('required' => false,
                                                                 'trim' => true
                                                                )
                                                          );
    $this->validatorSchema['name'] = new sfValidatorString(array('required' => false,
                                                                 'trim' => true
                                                                )
                                                          );

    $this->validatorSchema['classification'] = new sfValidatorChoice(array('choices'  => array_keys($classificationKeys), 'required' => true));
    $this->validatorSchema['table'] = new sfValidatorString(array('required' => true));
  }

  public function addCodeColumnQuery(Doctrine_Query $query, $field, $values)
  {
     if ($values != ""):
       $query->andWhere("upper(code) like concat(upper(?), '%') ", $values);
     endif;
     return $query;
  }

  public function addClassificationColumnQuery(Doctrine_Query $query, $field, $values)
  {
     if ($values != ""):
       $query->andWhere(" classification = ? ", $values);
     endif;
     return $query;
  }

  public function doBuildQuery(array $values)
  {
    $query = parent::doBuildQuery($values);
    $this->addNamingColumnQuery($query, 'mineralogy', 'name_indexed', $values['name']);
    $query->andWhere("id != 0 ")
          ->limit($this->getCatalogueRecLimits());
    return $query;
  }
}
