<?php

/**
 * Imports filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ImportsFormFilter extends BaseImportsFormFilter
{
  public function configure()
  { 
    $this->useFields(array('collection_ref', 'state','filename')) ;  
    $this->addPagerItems();  
    $collection_list = Doctrine::getTable('Collections')->getAllAvailableCollectionsFor($this->options['user_ref']) ;
    $state_list = Imports::getStateList() ;
    /* Widgets */
    $this->widgetSchema['collection_ref'] = new sfWidgetFormChoice(
      array(
        'choices' => $collection_list
      )
    );  
    
    $this->widgetSchema['state'] = new sfWidgetFormChoice(
      array(
        'choices' => $state_list
      )
    ); 
    $this->widgetSchema['filename'] = new sfWidgetFormInputText() ; 
    $this->widgetSchema['filename']->setAttributes(array('class'=>'small_size'));    
 //   $this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden() ;        
    /* Labels */
    $this->widgetSchema->setLabels(array('collection_ref' => 'Collections',
                                         'filename' => 'File Name',
                                         'state' => 'State',
                                        )
                                  );
                                  
    /* validators */                    
    $this->validatorSchema['collection_ref'] = new sfValidatorChoice(
      array('choices'=> array_keys($collection_list)));
  }
  
  public function doBuildQuery(array $values)
  {
    $query = DQ::create()
      ->from('Imports i')
      ->innerJoin("i.Collections")    ;  
    if($values['collection_ref'] != 0) $query->addWhere('i.collection_ref = ?', $values['collection_ref']) ;
    if($values['filename']) $query->addWhere('i.filename LIKE \'%'.$values['filename'].'%\'');
    if($values['state']) $query->addWhere('i.state = ?', $values['state']) ;    
    // here, add where clause to look for import file only where the user have right on collection 
    $query->andWhereIn('collection_ref',array_keys(
      Doctrine::getTable('Collections')->getAllAvailableCollectionsFor($this->options['user_ref']))
    );

    return $query ;
  }  
}