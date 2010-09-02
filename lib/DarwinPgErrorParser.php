<?php
class DarwinPgErrorParser
{
  // @TODO What happens when language of database is not english... Shouldn't we use invariable parts of message such as SQLSTATE[] and constraint names... ?
  protected $nat_exception = null;

  protected static $errorRegexps = array(
    '/Author still used as author/i' => 'This People is still referenced as an author',
    '/Impossible to impact children names/i' => 'Impossible to impact children names',
    '/Still Manager in some Collections/i' => '',
    '/follow the rules of possible upper level attachement/i' => 'The modification does not follow the rules of Upper levels attachements',
    '/Update of unit level break "possible_upper_levels" rule of direct children related/i' => 'The modification Does not follow the rules of Upper levels attachements',
    '/Impossible to update children Update of parent_ref/i' => 'Impossible to impact children names',
    '/Author must be defined as author/i' => 'The author must be defined as such to be attached',
    '/Experts must be defined as expert/i' =>  'The expert must be defined as such to be attached',
    '/Maximum number of renamed item reach/i' => 'Maximum number of "current names" reach',
    '/Maximum number of recombined item reach/i' => 'Maximum number of "recombination" reach',
    '/set this synonym twice/i' => 'You can\'t attach a synonym twice',
    '/Error in datesOverlaps function/i' => 'Error in dates overlaping',
    '/Duplicate key value violates unique constraint "unq_comments"/i' => 'You cannot add a particular comment notion twice',
    '/duplicate key value violates unique constraint "unq_comments"/i' => 'You cannot add a particular comment notion twice',
    '/duplicate key value violates unique constraint "unq_specimens"/i' => 'This specimen already exist',
    '/duplicate key value violates unique constraint "unq_specimen_individuals"/i' => 'This individual already exist for this specimen',
    '/duplicate key value violates unique constraint "unq_catalogue_properties"/i' => 'This property already exist',
    '/unq_collecting_methods/i' => 'This method already exist',
    '/unq_collecting_tools/i' => 'This tool already exist',
    '/violates check constraint ".*_minmax"/i' => 'Max count value must be superior or equal to Min count value',
    '/violates check constraint ".*_min"/i' => 'Min count value must be a positive number',
    '/violates check constraint "chk_collecting_methods_method"/i' => 'Method inserted cannot be empty',
    '/violates check constraint "chk_collecting_tools_tool"/i' => 'Tool inserted cannot be empty',
    '/unq_users/i' => 'This user already exist'
  );


  public function __construct(Doctrine_Exception $e)
  {
    $this->nat_exception = $e;
  }

  
  public function getMessage()
  {
    $original_message = $this->nat_exception->getMessage();
    foreach (self::$errorRegexps as $regexp => $message)
    {
	  if(preg_match($regexp, $original_message))
	  {
	      return $message;
	  }
    }
    return 'Unknown Error : '.$original_message;
  }
}
