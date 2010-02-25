<?php
class DarwinPgErrorParser
{
  protected $nat_exception = null;

  protected static $errorRegexps = array(
    '/Author still used as author/i' => 'This People is still referenced as an author',
    '/Impossible to impact children names/i' => 'Impossible to impact children names',
    '/Still Manager in some Collections/i' => '', // @TODO ??
    '/follow the rules of possible upper level attachement/i' => 'The modification Does not follow the rules of Upper levels attachements',
    '/Update of unit level break "possible_upper_levels" rule of direct children related/i' => 'The modification Does not follow the rules of Upper levels attachements',
    '/Impossible to update children Update of parent_ref and/or level_ref of current unit aborted/i' => 'Impossible to impact children names',
    '/Author must be defined as author/i' => 'The author must be defined as such to be attached',
    '/Experts must be defined as expert/i' =>  'The expert must be defined as such to be attached',
    '/Maximum number of renamed item reach/i' => 'Maximum number of "current names" reach',
    '/Maximum number of recombined item reach/i' => 'Maximum number of "recombination" reach',
    '/set this synonym twice/i' => 'You can\'t attach a synonym twice',
    '/Error in datesOverlaps function/i' => 'Error in dates overlaping',
  );


  public function __construct(Doctrine_Exception $e)
  {
    $this->nat_exception = $e;
  }

  
  public function getMessage()
  {
    $original_message = $this->nat_exception->getMessage();
    if($this->nat_exception->getPortableCode() == null)
    {
      foreach (self::$errorRegexps as $regexp => $message)
      {
	if(preg_match($regexp, $original_message))
	{
                return $message;
        }
      }
      return 'Unknown Error : '.$original_message;
    }
    else
    {
      return $this->nat_exception->getPortableMessage();
    }
  }
}