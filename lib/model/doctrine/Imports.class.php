<?php

/**
 * Imports
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Imports extends BaseImports
{
  protected $line_num = 0;
  private static $state = array(
    '' => 'All', 
    'to_be_loaded' => 'To be loaded',
    'loading'=> 'Loading',
    'loaded'=>'Loaded',
    'checking'=> 'Checking',
    'pending'=> 'Pending',
    'processing'=> 'Processing',
    'finished' => 'Finished',
    'aborted' => 'Aborted',
  );  
  private static $info = array(
    'to_be_loaded' => 'This file is ready to be loaded, an automatic task will be activated to load lines.',
    'loading'=> 'The file is actually being loaded in database',
    'loaded'=>'Your file has been loaded, but still need to be checked',
    'checking'=> 'Your file has been loaded and is being checked',
    'pending'=> 'Your file has been loaded and checked, you can edit line in errors or import corrects lines',
    'processing'=> 'Your \'Ok\' lines are beeing imported in DaRWIN',
    'finished' => 'This file has been completly been imported in DaRWIN',
    'aborted' => 'This file has been aborted. This line will remain for a limited time in the summary list just for information purposes only.',    
  );  

  public static $formatArray = array('dna' => 'DNA') ;
  
  public function setCurrentLineNum($nbr)
  {
    $this->line_num = $nbr;
  }
  public function getCurrentLineNum()
  {
    return $this->line_num;
  }

  public static function getFormats()
  {
    return self::$formatArray ;
  }
  
  public function getStateName($name = null)
  {
    if($name) return self::$state[$name];
    return self::$state[$this->getState()];
  }
  public static function getStateList()
  {
    return self::$state ;
  } 
   
  public function getStateInfo($state)
  {
    return self::$info[$state];
  }
  
  // function used to determine if we can display edition button or not
  public function isEditableState()
  {
    if(in_array($this->getState(),array('pending')) && ! $this->getIsFinished()) return true ;
    return false ;
  }   

  public function getLastModifiedDate()
  {
    
    $dateTime = new FuzzyDateTime($this->_get('updated_at')!=''?$this->_get('updated_at'):$this->_get('created_at'));
    return $dateTime->getDateMasked('em','d/m/Y H:i');
  }  
}