<?php

/**
 * Preferences form.
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PreferencesForm extends sfForm 
{
  public function configure()
  {
    $pref_keys = array('spec_search_cols');
    $this->db_keys = Doctrine::getTable('Preferences')->getAllPreferences($this->options['user']->getId(), $pref_keys);
    
    $choices = Doctrine::getTable('MySavedSearches')->getAllFields() ;

    $this->widgetSchema['spec_search_cols'] = new sfWidgetFormChoice(array(
      'choices' => $choices, 
      'expanded' => true,
      'multiple' => true,
      'renderer_options' => array('formatter' => array($this, 'formatter'))     
    ));
    $this->widgetSchema['spec_search_cols']->setLabel('Default visible columns');
    $defaut = $this->db_keys['spec_search_cols'];
    if($default == '')
      $default = 'category|taxon|collection|type|gtu';
    $this->widgetSchema['spec_search_cols']->setDefault(explode('|',$default));
    
    $this->widgetSchema->setHelp('spec_search_cols', 'Define which field will be available by default into the specimen search');

    $this->validatorSchema['spec_search_cols'] = new sfValidatorChoice(array('choices' => $choices, 'multiple' => true));
    $this->widgetSchema->setNameFormat('preferences[%s]');
  }

  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $i => $input)
    {
      $rows[] = $widget->renderContentTag(
            'tr',
            '<td>'.$input['label'].'</td><td>'.$input['input'].'</td>'
           );
    }
    return $widget->renderContentTag('tbody', implode($widget->getOption('separator'), $rows));
  }

  public function save($con = null)
  {
    $results = array(
      'spec_search_cols'=> implode('|',$this->getValue('spec_search_cols'))
    );
    Doctrine::getTable('Preferences')->saveAllPreferences($this->options['user']->getId(),$results);
  }
}
