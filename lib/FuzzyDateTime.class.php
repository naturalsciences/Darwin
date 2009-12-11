<?php

/*
 * This file is part of the Darwin2 package.
 * (c) Paul-André Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * FuzzyDateTime is an object that will contain a date and an applied mask to render the fuzzyness
 *
 * @package    darwin2
 * @subpackage lib
 * @author     Paul-Andre Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
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
   * @param string/array  $dateTime  A string or an array representing a valid date/time
   * @param integer       $mask      An integer representing a mask value - sum of each integer representing the mask part: 32 for years, 16 for months,...
   * @param boolean       $start     An optional parameter to 
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

  public static function getDefaultMaxArray()
  {
    if(!isset( self::$defaultMaxValues['year']) )
        self::$defaultMaxValues['year'] = sfConfig::get('app_yearUpperBound');
    return self::$defaultMaxValues;
  }

  public static function getDefaultMinArray()
  {
    return self::$defaultValues;
  }

  public static function validateDateField($field, $value)
  {
    $max_array = self::getDefaultMaxArray();
    $min_array = self::getDefaultMinArray();
    if(is_numeric($value) && intval($value)>= $min_array[$field] && intval($value) <= $max_array[$field])
      return true;
    return false;
  }

  public static function checkDateArray(array $dateTime)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      //Should we thest '' ?
      if (isset($dateTime[$key]) && $dateTime[$key] !== '' && !self::validateDateField($key,$dateTime[$key]) )
      {
	return 'wrong_date_part_length';
      }
    }
    return '';
  }

  public static function getDateTimeStringFromArray(array $dateTime, $start=true, $withTime=false)
  {
    $min_array = self::getDefaultMinArray();
    $max_array = self::getDefaultMaxArray();

    if (!self::checkDateArray($dateTime) == '')
    {
      if($start)
	$dateTime = $min_array;
      else
	$dateTime = $max_array;
    }
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
    $dateTime = sprintf('%04d/%02d/%02d' .($withTime?' %02d:%02d:%02d':''), $year, $month, $day, $hour, $minute, $second);
    return $dateTime;
  }

  public function setMask($mask=0)
  {
    $this->mask = $mask;
  }

  public function setStart($start=true)
  {
    $this->start = $start;
  }
  
  public function setWithTime($withTime=false)
  {
    $this->withTime = $withTime;
  }

  public function setDateFormat($dateFormat='d/m/Y')
  {
    $this->dateFormat = $dateFormat;
  }

  public function setTimeFormat($timeFormat='H:i:s')
  {
    $this->timeFormat = $timeFormat;
  }

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

  public static function getMaskFor($key)
  {
    return self::$datePartsMask[$key];
  }

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
	  if($has_an_empty === null) // we got en empty... if no value after that, it's ok
	    $has_an_empty = $i;
	}
      }
      return '';
  }

  public static function getMaskFromDate(array $dateTime)
  {
    $mask = 0;
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($dateTime[$key]) && self::validateDateField($key, $dateTime[$key]))
      {
	$mask += self::getMaskFor($key);
      }
      else
      {
	break;
      }
    }
    return $mask;
  }

  public function getDateTime($withTime=null, $dateFormat = null, $timeFormat=null)                                                
  {                                                                                                                                
    $withTime = (is_null($withTime)) ? $this->getWithTime() : $withTime;                                                               
    $dateFormat = (is_null($dateFormat)) ? $this->getDateFormat() : $dateFormat;
    $timeFormat = (is_null($timeFormat)) ? $this->getTimeFormat() : $timeFormat;
    return $this->format($dateFormat.(($withTime) ? ' '.$timeFormat : ''));
  }

  public function getTime($timeFormat=null)
  {
    $timeFormat = (is_null($timeFormat)) ? $this->getTimeFormat() : $timeFormat;
    return $this->format($timeFormat);
  }

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
  
  public function getDateTimeMaskedAsArray()
  {
    $date = $this->getDateTimeAsArray();
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
        if(! (self::getMaskFor($key) & $this->getMask()) )
	  $date[$key]='';
    }
    return $date;
  }

  public function getDateMasked($tag='em')
  {
    $firstPart = '';
    $lastPart = '';
    $yearAtLeast = self::$datePartsMask['year'] & $this->getMask();
    if (!$yearAtLeast)
    {
      $mainPart = '<'.$tag.'>'.strval($this->getDateTime($this->getWithTime())).'</'.$tag.'>';
    }
    elseif (!(self::$datePartsMask['month'] & $this->getMask()))
    {
      $firstPart = '<'.$tag.'>'.strval($this->getDateTime(false, 'd/m/')).'</'.$tag.'>';
      $mainPart = strval($this->getDateTime(false, 'Y'));
    }
    elseif (!(self::$datePartsMask['day'] & $this->getMask()))
    {
      $firstPart = '<'.$tag.'>'.strval($this->getDateTime(false, 'd/')).'</'.$tag.'>';
      $mainPart = strval($this->getDateTime(false, 'm/Y'));
    }
    else
    {
      $mainPart = strval($this->getDateTime(false));
    }
    if ($this->getWithTime() && $yearAtLeast)
    {
      if (!(self::$datePartsMask['hour'] & $this->getMask()))
      {
        $lastPart = '<'.$tag.'> '.strval($this->getTime()).'</'.$tag.'>';
      }
      elseif (!(self::$datePartsMask['minute'] & $this->getMask()))
      {
        $mainPart .= ' '.$this->getTime('H');
        $lastPart = '<'.$tag.'>'.strval($this->getTime(':i:s')).'</'.$tag.'>';
      }
      elseif (!(self::$datePartsMask['second'] & $this->getMask()))
      {
        $mainPart .= ' '.$this->getTime('H:i');
        $lastPart = '<'.$tag.'>'.strval($this->getTime(':s')).'</'.$tag.'>';
      }
      else
      {
        $mainPart .= ' '.$this->getTime();
      }
    }
    return $firstPart.$mainPart.$lastPart;
  }

  public function __ToString()
  {
    return $this->getDateTime($this->getWithTime(), $this->getDateFormat(), $this->getTimeFormat());
  }
  
}
