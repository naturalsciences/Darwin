<?php
/**
 * FuzzyDateTime is an object that will contain a date/time and an applied mask to render the fuzzyness
 *
 * @package    darwin
 * @subpackage lib
 * @category   object
 * @author     DB team <collections@naturalsciences.be>
 * @staticvar  array   $defaultValues: List of default values for each date/time part: year, month,...
 * @staticvar  array   $defaultMaxValues: List of default max values for all date/time parts except year
 * @staticvar  array   $datePartsMask: List of values used for the mask and telling a given part was encoded for the date/time: 32 for year, 16 for month,...
 * @var        boolean $start: Flag used for date auto-completion when some parts are missing - if start is true, bring the first day when day is missing and if start is false, bring the last day in month,...
 * @var        int     $mask : Total mask value - comes as a sum of all date parts mask illustrating what have been encoded
 * @var        boolean $withTime: Flag telling if what's expected to be brought back is a date or a date/time
 * @var        string  $dateFormat: Date format by default
 * @var        string  $timeFormat: Time format by default
 */
class FuzzyDateTime extends DateTime
{
  protected static
    $defaultValues = array('year'=>'0001', 'month'=>'01', 'day'=>'01', 'hour'=>'00', 'minute'=>'00', 'second'=>'00'),
    $defaultMaxValues = array('month'=>'12', 'day' => '31','hour'=>'23', 'minute'=>'59', 'second'=>'59'),
    $datePartsMask = array('year'=>32, 'month'=>16, 'day'=>8, 'hour'=>4, 'minute'=>2, 'second'=>1);
  protected
    $start = true,
    $mask = 0,
    $withTime = false,
    $dateFormat = 'd/m/Y',
    
    $timeFormat = 'H:i:s';
  
 /**
   * Configures the current date/time object.
   *
   * @param          string/array  $dateTime  A string or an array representing a valid date/time
   * @param          integer       $mask      An integer representing a mask value - sum of each integer representing the mask part: 32 for years, 16 for months,...
   * @param          boolean       $start     An optional parameter to tell if the date entered is a strat or an end date
   * @param          boolean       $withTime  An optional parameter to tell if what's stored is expected to be a date or a date and time
   *
   */ 
  public function __construct($dateTime='now', $mask=0, $start=true, $withTime=false)
  {
    if (is_array($dateTime))
      $dateTime = self::getDateTimeStringFromArray($dateTime, $start, $withTime);
    parent::__construct($dateTime);
    $this->setMask($mask);
    $this->setStart($start);
    $this->setWithTime($withTime);
  }

 /**
   * Return the array of the default max values
   *
   * @return array self::$defaultMaxValues
   *
   */ 
  public static function getDefaultMaxArray()
  {
    // If static max year value is not defined, take it from a config parameter in a config file
    if(!isset( self::$defaultMaxValues['year']) )
        self::$defaultMaxValues['year'] = sfConfig::get('app_yearUpperBound');
    return self::$defaultMaxValues;
  }

 /**
   * Return the array of the default min values
   *
   * @return array self::$defaultValues
   *
   */ 
  public static function getDefaultMinArray()
  {
    return self::$defaultValues;
  }

 /**
   * Check that a date part (year, month, day,...) is well numeric and between the min and max values
   *
   * @param  string  $field     Name of the date part - field to test
   * @param  int     $value     Value to be tested
   * @return boolean
   *
   */ 
  public static function validateDateField($field, $value)
  {
    $max_array = self::getDefaultMaxArray();
    $min_array = self::getDefaultMinArray();
    if(is_numeric($value) && intval($value)>= $min_array[$field] && intval($value) <= $max_array[$field])
      return true;
    return false;
  }

 /**
   * Check that each part of a date array passed as parameter is ok
   *
   * @param  array     $dateTime  Array of date and time values to be checked
   * @return string
   *
   */ 
  public static function checkDateArray(array $dateTime)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($dateTime[$key]) && $dateTime[$key] !== '' && !self::validateDateField($key,$dateTime[$key]) )
      {
	return 'wrong_date_part_length';
      }
    }
    return '';
  }

 /**
   * Transform a date/time array in a date/time string
   *
   * @param  array     $dateTime  Array of date and time values
   * @param  boolean   $start     Tells if date/time brought is a start or end date/time: helps to compose the missing parts
   * @param  boolean   $withTime  Tells if the string to return is a date with or without time
   * @return string
   *
   */ 
  public static function getDateTimeStringFromArray(array $dateTime, $start=true, $withTime=false)
  {
    $min_array = self::getDefaultMinArray();
    $max_array = self::getDefaultMaxArray();

    // Check each date/time array parts are ok
    if (!self::checkDateArray($dateTime) == '')
    {
      // If not takes eather default min or max value
      if($start)
        $dateTime = $min_array;
      else
        $dateTime = $max_array;
    }
    // Makes the replacement of empty parts by default values depending of it's a start or an end date/time
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (!isset($dateTime[$key]) || empty($dateTime[$key]))
      {
        if ($start)
        {
          $$key = $min_array[$key];
        }
        else
        {
	  if($key == 'day')
	  {
            // Compute the max days in month
	    $maxDaysInMonth = new DateTime("$year/$month/01");
	    $$key = $maxDaysInMonth->format('t');
	  }
	  else
          {
            $$key = $max_array[$key];
          }
        }
      }
      else
      {   
          $$key = $dateTime[$key];
      }
    }
    
    // Format the string to be produced
    $dateTime = sprintf('%04d/%02d/%02d' .($withTime?' %02d:%02d:%02d':''), $year, $month, $day, $hour, $minute, $second);
    return $dateTime;
  }

 /**
   * Sets the object mask
   *
   * @param  int     $mask       Mask value
   *
   */ 
  public function setMask($mask=0)
  {
    $this->mask = $mask;
  }

 /**
   * Sets the object start value: true if a start date/time, false if an end date/time
   *
   * @param  boolean   $start       start value
   *
   */ 
  public function setStart($start=true)
  {
    $this->start = $start;
  }
  
 /**
   * Sets the object withTime value: true if the object stores date and time and false if only a date
   *
   * @param  boolean   $withTime       with time value
   *
   */ 
  public function setWithTime($withTime=false)
  {
    $this->withTime = $withTime;
  }

 /**
   * Sets the object date format for the date stored in object display
   *
   * @param  string   $dateFormat        date format value
   *
   */ 
  public function setDateFormat($dateFormat='d/m/Y')
  {
    $this->dateFormat = $dateFormat;
  }

 /**
   * Sets the object time format for the time stored in object display
   *
   * @param  string   $timeFormat        time format value
   *
   */ 
  public function setTimeFormat($timeFormat='H:i:s')
  {
    $this->timeFormat = $timeFormat;
  }

 /**
   * Sets the object mask from a date time array passed as parameter
   *
   * @param  array   $dateTime   The array to be parsed to determine the mask to store in object
   *
   */ 
  public function setMaskFromDate(array $dateTime)
  {
    $this->mask = self::getMaskFromDate($dateTime);
  }

  public function getMask()
  {
    return $this->mask;
  }
  
  public function getStart()
  {
    return $this->start;
  }

  public function getWithTime()
  {
    return $this->withTime;
  }
  
  public function getDateFormat()
  {
    return $this->dateFormat;
  }
  
  public function getTimeFormat()
  {
    return $this->timeFormat;
  }

 /**
   * Returns the mask value corresponding to a given date/time part
   *
   * @param  string  $key   Date/Time part
   * @return int
   *
   */ 
  public static function getMaskFor($key)
  {
    return self::$datePartsMask[$key];
  }

 /**
   * Returns the name of the empty or invalid date/time part followed by _missing
   *
   * @param  array      $dateTime     Array of a date/time
   * @return string
   *
   */ 
  public static function checkDateTimeStructure (array $dateTime)
  {
      $checkDate = self::checkDateArray($dateTime);
      $has_an_empty = null;
      $items = array('year', 'month', 'day', 'hour', 'minute', 'second');
      foreach ($items as $i => $key)
      {
	if( isset($dateTime[$key]) && self::validateDateField($key, $dateTime[$key]) )
	{
	  if($has_an_empty === null)
	    continue; //untill there is a filled value
	  return $items[$has_an_empty].'_missing';
	}
	else
	{
	  if($has_an_empty === null) // we got an empty... if no value after that, it's ok
	    $has_an_empty = $i;
	}
      }
      return '';
  }

 /**
   * Return the mask (summed) value for a date/time array passed as parameter
   *
   * @param  array   $dateTime   The date/time array to be parsed to determine the associated mask (sum of each mask value defined for each parts encoded)
   * @return int     $mask
   *
   */ 
  public static function getMaskFromDate(array $dateTime)
  {
    $mask = 0;
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      // For each part found, and if part is valid, increment the mask value with the mask associated to the date part analyzed
      if (isset($dateTime[$key]) && self::validateDateField($key, $dateTime[$key]))
      {
        $mask += self::getMaskFor($key);
      }
      else
      {
        // Stop at the time a part's missing
        break;
      }
    }
    return $mask;
  }

 /**
   * Returns the date/time stored in object formated
   *
   * @param  boolean   $withTime   Should the string returned contains a time
   * @param  string    $dateFormat Date format to be applied
   * @param  string    $timeFormat Time format to be applied
   * @return string    Date/Time formated
   *
   */ 
  public function getDateTime($withTime=null, $dateFormat = null, $timeFormat=null)                                                
  {                                                                                                                                
    $withTime = (is_null($withTime)) ? $this->getWithTime() : $withTime;                                                               
    $dateFormat = (is_null($dateFormat)) ? $this->getDateFormat() : $dateFormat;
    $timeFormat = (is_null($timeFormat)) ? $this->getTimeFormat() : $timeFormat;
    return $this->format($dateFormat.(($withTime) ? ' '.$timeFormat : ''));
  }

 /**
   * Returns the time stored in object formated
   *
   * @param  string    $timeFormat Time format to be applied
   * @return string    Time formated
   *
   */ 
  public function getTime($timeFormat=null)
  {
    $timeFormat = (is_null($timeFormat)) ? $this->getTimeFormat() : $timeFormat;
    return $this->format($timeFormat);
  }

 /**
   * Returns the date/time stored in object as an array
   *
   * @return array
   *
   */ 
  public function getDateTimeAsArray()
  {
    return array('year' => intval($this->format('Y')), 
                 'month' => intval($this->format('m')), 
                 'day' => intval($this->format('d')), 
                 'hour' => intval($this->format('H')), 
                 'minute' => intval($this->format('i')), 
                 'second' => intval($this->format('s'))
                );
  }
  
 /**
   * Returns the date/time stored in object with mask applied as a formated string
   *
   * @return array
   *
   */ 
  public function getDateTimeMaskedAsArray()
  {
    $date = $this->getDateTimeAsArray();
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
        // If no mask found for the key parsed, remove the key value from array to be returned
        if(! (self::getMaskFor($key) & $this->getMask()) )
          $date[$key]='';
    }
    return $date;
  }

 /**
   * Returns the date/time stored in object as a string formated with a tag for each parts that should be masked (missing parts at encoding moment)
   *
   * @param  string  $tag         The tag that should surround the date/time part to be masked 
   * @return array
   *
   */ 
  public function getDateMasked($tag='em', $format=null)
  {
    if($format==null)
    {
      $format = $this->dateFormat;
      if($this->getWithTime())
	$format .= ' '.$this->timeFormat;
    }

    $patterns = array(
      'year' => array('Y','y','o'),
      'month' => array('n','F','m','M','t'),
      'day' => array('d','D','j','l','N','w','z'),
      'hour' => array('g','G','h','H','a','A'),
      'minute' => array('i') ,
      'second' => array('s','U') 
    );

    foreach(self::$datePartsMask as $date_part => $associated_mask)
    {
      if( ! ($associated_mask & $this->getMask()) )
      {
	 foreach($patterns[$date_part] as $pattern)
	 {
	    
	    $format = preg_replace("/($pattern)/", '§$1£',$format);
	 }
      }
    }
    $result = $this->format($format);
//     print $result ."-".$this->getMask()."\n";

//
    $result = preg_replace('|£([\-\/\\\ \:])§|','$1',$result); //Replace if tag are joined replace 2 tag by one big
    $result = preg_replace(array('/§/','/£/'), array("<$tag>","</$tag>"),$result);
    return $result;
  }

 /**
   * Returns the date/time stored in object as a formated string
   *
   * @return string
   *
   */ 
  public function __ToString()
  {
    return $this->getDateTime($this->getWithTime(), $this->getDateFormat(), $this->getTimeFormat());
  }
  
}
