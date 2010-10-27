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
    $pref_keys = array('search_cols_specimen', 'search_cols_individual', 'search_cols_part', 'board_search_rec_pp', 'board_spec_rec_pp');
    $this->db_keys = Doctrine::getTable('Preferences')->getAllPreferences($this->options['user']->getId(), $pref_keys);
    $is_reg_user = $this->options['user']->isA(Users::REGISTERED_USER) ;
    $choices = Doctrine::getTable('MySavedSearches')->getAllFields('specimen') ;

    $this->widgetSchema['search_cols_specimen'] = new sfWidgetFormChoice(array(
      'choices' => $choices, 
      'expanded' => true,
      'multiple' => true,
      'renderer_options' => array('formatter' => array($this, 'formatter'))
    ));
    $this->widgetSchema['search_cols_specimen']->setLabel('Default visible columns');
    $default = $this->db_keys['search_cols_specimen'];
    if($default == '')
      $default = Doctrine::getTable('Preferences')->getDefaultValue('search_cols_specimen');
    $this->widgetSchema['search_cols_specimen']->setDefault(explode('|',$default));
    $this->widgetSchema->setHelp('search_cols_specimen', 'Define which field will be available by default into the specimen search');
    $this->validatorSchema['search_cols_specimen'] = new sfValidatorChoice(array('choices' => $choices, 'multiple' => true));

///------------- INDIVIDUALS
    $choices = Doctrine::getTable('MySavedSearches')->getAllFields('individual') ;

    $this->widgetSchema['search_cols_individual'] = new sfWidgetFormChoice(array(
      'choices' => $choices, 
      'expanded' => true,
      'multiple' => true,
      'renderer_options' => array('formatter' => array($this, 'formatter'))
    ));
    $this->widgetSchema['search_cols_individual']->setLabel('Default visible columns');
    $default = $this->db_keys['search_cols_individual'];
    if($default == '')
      $default = Doctrine::getTable('Preferences')->getDefaultValue('search_cols_individual');
    $this->widgetSchema['search_cols_individual']->setDefault(explode('|',$default));
    $this->widgetSchema->setHelp('search_cols_individual', 'Define which field will be available by default into the specimen search');
    $this->validatorSchema['search_cols_individual'] = new sfValidatorChoice(array('choices' => $choices, 'multiple' => true));

///------------ PARTS
    $choices = Doctrine::getTable('MySavedSearches')->getAllFields('part',$is_reg_user) ;

    $this->widgetSchema['search_cols_part'] = new sfWidgetFormChoice(array(
      'choices' => $choices, 
      'expanded' => true,
      'multiple' => true,
      'renderer_options' => array('formatter' => array($this, 'formatter'))
    ));
    $this->widgetSchema['search_cols_specimen']->setLabel('Default visible columns');
    $default = $this->db_keys['search_cols_part'];
    if($default == '')
      $default = Doctrine::getTable('Preferences')->getDefaultValue('search_cols_part');
    $this->widgetSchema['search_cols_part']->setDefault(explode('|',$default));
    $this->widgetSchema->setHelp('search_cols_part', 'Define which field will be available by default into the specimen search');
    $this->validatorSchema['search_cols_part'] = new sfValidatorChoice(array('choices' => $choices, 'multiple' => true));
///-----OTHER

    $choices = array('5' => '5', '10' => '10', '15' => '15', '20' => '20');
    $this->widgetSchema['board_search_rec_pp'] = new sfWidgetFormChoice(array('choices' => $choices));
    $this->validatorSchema['board_search_rec_pp'] = new sfValidatorChoice(array('choices' => array_keys($choices) ));
    $this->widgetSchema['board_search_rec_pp']->setLabel('Number of saved searches');
    $this->widgetSchema->setHelp('board_search_rec_pp',"Number of Saved searches showed on the board widget. (You browse every searches on the dedicated page)");
    $this->widgetSchema['board_search_rec_pp']->setDefault($this->db_keys['board_search_rec_pp']? $this->db_keys['board_search_rec_pp'] : Doctrine::getTable('Preferences')->getDefaultValue('board_search_rec_pp'));

    $this->widgetSchema['board_spec_rec_pp'] = new sfWidgetFormChoice(array('choices' => $choices));
    $this->validatorSchema['board_spec_rec_pp'] = new sfValidatorChoice(array('choices' => array_keys($choices) ));
    $this->widgetSchema['board_spec_rec_pp']->setLabel('Number of saved specimens');
    $this->widgetSchema->setHelp('board_spec_rec_pp',"Number of saved specimens list showed on the board widget. (You browse every specimen lists on the dedicated page)");
    $this->widgetSchema['board_spec_rec_pp']->setDefault($this->db_keys['board_spec_rec_pp']? $this->db_keys['board_spec_rec_pp'] : Doctrine::getTable('Preferences')->getDefaultValue('board_spec_rec_pp'));

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
      'search_cols_specimen'=> implode('|',$this->getValue('search_cols_specimen')),
      'search_cols_individual'=> implode('|',$this->getValue('search_cols_individual')),
      'search_cols_part'=> implode('|',$this->getValue('search_cols_part')),
      'board_search_rec_pp'=> $this->getValue('board_search_rec_pp'),
      'board_spec_rec_pp'=> $this->getValue('board_spec_rec_pp'),
    );
    Doctrine::getTable('Preferences')->saveAllPreferences($this->options['user']->getId(),$results);
  }
}
