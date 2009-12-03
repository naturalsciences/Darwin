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
    $defaultYear = '0001',
    $defaultMonth = '01',
    $defaultMaxMonth = '12',
    $defaultDay = '01',
    $defaultHour = '00',
    $defaultMaxHour = '23',
    $defaultMinute = '00',
    $defaultMaxMinute = '59',
    $defaultSecond = '00',
    $defaultMaxSecond = '59',
    $datePartsMask = array('year'=>32, 'month'=>16, 'day'=>8, 'hour'=>4, 'minute'=>2, 'second'=>1);
  protected
    $start = true,
    $mask = 56,
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
  public function __construct($dateTime='now', $mask=56, $start=true)
  {
    if (is_array($dateTime)) $dateTime = getDateStringFromArray($dateTime, $start);
    parent::__construct($dateTime);
    $this->setStart($start);
    $this->setMask($mask);
  }

  public static function validateDateYearLength ($year)
  {
    return (strlen(strval($year))<=4);
  }
  
  public static function validateDateOtherPartLength ($part)
  {
    return (strlen(strval($part))<=2);
  }

  public static function checkDateArray(array $dateTime)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($dateTime[$key]) && !empty($dateTime[$key]))
      {
        if (!preg_match('#^\d+$#', $dateTime[$key]))
        {
          throw new Exception('All elements in the date array must be a number or an empty value !');
        }
        elseif ($key == 'year' && !self::validateDateYearLength($dateTime[$key]))
        {
          throw new Exception('Year cannot be more than 4 digit length !');
        }
        elseif ($key != 'year' && !self::validateDateOtherPartLength($dateTime[$key]))
        {
          throw new Exception('Date part other than years cannot be more than 2 digit length !');
        }
      }
    }
    return true;
  }

  public static function getDateTimeStringFromArray(array $dateTime, $start=false, $withTime=false)
  {
    if (!self::checkDateArray($dateTime)) return (self::$defaultYear).'/'.(self::$defaultMonth).'/'.(self::$defaultDay).(($withTime)?' '.(self::$defaultHour).':'.(self::$defaultMinute).':'.(self::$defaultSsecond):'');
    $year = (!isset($dateTime['year']) || empty($dateTime['year']))?(($start)?self::$defaultYear:strval(date('Y'))):str_pad(strval($dateTime['year']), 4, '0', STR_PAD_LEFT);
    $month = (!isset($dateTime['month']) || empty($dateTime['month']))?(($start)?self::$defaultMonth:self::$defaultMaxMonth):str_pad(strval($dateTime['month']), 2, '0', STR_PAD_LEFT);
    $maxDaysInMonth = new DateTime(strval(($month == '12')?intval($year)+1:$year).'/'.(($month == '12')?'01':intval($month)+1).'/00');
    $day = (!isset($dateTime['day']) || empty($dateTime['day']))?(($start)?self::$defaultDay:str_pad(strval($maxDaysInMonth->format('d')), 2, '0', STR_PAD_LEFT)):str_pad(strval($dateTime['day']), 2, '0', STR_PAD_LEFT);
    $hour = (!isset($dateTime['hour']) || empty($dateTime['hour']))?(($start)?self::$defaultHour:self::$defaultMaxHour):str_pad(strval($dateTime['hour']), 2, '0', STR_PAD_LEFT);
    $minute = (!isset($dateTime['minute']) || empty($dateTime['minute']))?(($start)?self::$defaultMinute:self::$defaultMaxMinute):str_pad(strval($dateTime['minute']), 2, '0', STR_PAD_LEFT);
    $second = (!isset($dateTime['second']) || empty($dateTime['second']))?(($start)?self::$defaultSecond:self::$defaultMaxSecond):str_pad(strval($dateTime['second']), 2, '0', STR_PAD_LEFT);
    $dateTime = $year.'/'.$month.'/'.$day.(($withTime)?' '.$hour.':'.$minute.':'.$second:'');
    return $dateTime;
  }

  public function setMask($mask=56)
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
    $this->mask = $this->getMaskFromDate($dateTime);
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

  public function getMaskFor($key)
  {
    return self::$datePartsMask[$key];
  }
  
  public function getMaskFromDate(array $dateTime)
  {
    $mask = 0;
    if(self::checkDateArray($dateTime))
    {
      foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
      {
        if (isset($dateTime[$key]) && !empty($dateTime[$key]))
        {
          $mask += $this->getMaskFor($key);
        }
        else
        {
          break;
        }
      }
    }
    return $mask;
  }
  
  public function getDateTime($withTime=null, $dateFormat = null, $timeFormat=null)
  {
    $withTime = (is_null($withTime))?$this->getWithTime():$withTime;
    $dateFormat = (is_null($dateFormat))?$this->getDateFormat():$dateFormat;
    $timeFormat = (is_null($timeFormat))?$this->getTimeFormat():$timeFormat;
    return $this->format($dateFormat.(($withTime)?' '.$timeFormat:''));
  }
  
  public function getDateTimeAsArray()
  {
    return array('year'=>strval($this->format('Y')), 
                 'month'=>strval($this->format('m')), 
                 'day'=>strval($this->format('d')), 
                 'hour'=>strval($this->format('H')), 
                 'minute'=>strval($this->format('i')), 
                 'second'=>strval($this->format('s'))
                );
  }
  
  public function getDateMasked($tag='em')
  {
  }

  public function __ToString()
  {
    return strval($this->getDateTime($this->getWithTime(), $this->getDateFormat(), $this->getTimeFormat()));
  }
  
}
