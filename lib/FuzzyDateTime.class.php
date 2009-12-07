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
    $defaultMaxValues = array('month'=>'12', 'hour'=>'23', 'minute'=>'59', 'second'=>'59'),
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
  public function __construct($dateTime='now', $mask=56, $start=true, $withTime=false)
  {
    if (is_array($dateTime)) $dateTime = self::getDateTimeStringFromArray($dateTime, $start, $withTime);
    parent::__construct($dateTime);
    $this->setMask($mask);
    $this->setStart($start);
    $this->setWithTime($withTime);
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
        if (!preg_match('#^\d+$#', $dateTime[$key])) return 'wrong_data_type';
        if ($key == 'year' && !self::validateDateYearLength($dateTime[$key])) return 'wrong_year_length';
        if ($key != 'year' && !self::validateDateOtherPartLength($dateTime[$key])) return 'wrong_date_part_length';
      }
    }
    return '';
  }

  public static function getDateTimeStringFromArray(array $dateTime, $start=true, $withTime=false)
  {
    if (!self::checkDateArray($dateTime)=='') return (self::$defaultValues['year']).'/'.(self::$defaultValues['month']).'/'.(self::$defaultValues['day']).(($withTime)?' '.(self::$defaultValues['hour']).':'.(self::$defaultValues['minute']).':'.(self::$defaultValues['second']):'');
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if ($key == 'day') $maxDaysInMonth = new DateTime(strval(($month == '12')?intval($year)+1:$year).'/'.(($month == '12')?'01':intval($month)+1).'/00');
      if (!isset($dateTime[$key]) || empty($dateTime[$key]))
      {
        if ($start)
        {
          $$key = self::$defaultValues[$key];
        }
        else
        {
          if ($key =='year')
          {
            $$key = strval(date('Y'));
          }
          elseif ($key == 'day')
          {
            $$key = strval($maxDaysInMonth->format('d'));
          }
          else
          {
            $$key = self::$defaultMaxValues[$key];
          }
        }
      }
      else
      {
        if ($key =='year')
        {
          $$key = str_pad(strval($dateTime[$key]), 4, '0', STR_PAD_LEFT);
        }
        else
        {
          $$key = str_pad(strval($dateTime[$key]), 2, '0', STR_PAD_LEFT);
        }
      }
    }
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
    if (!empty($checkDate)) return $checkDate;
    if ((!isset($dateTime['year']) || empty($dateTime['year'])) && ((isset($dateTime['month']) && !empty($dateTime['month'])) || 
                                                                    (isset($dateTime['day']) && !empty($dateTime['day'])) || 
                                                                    (isset($dateTime['hour']) && !empty($dateTime['hour'])) || 
                                                                    (isset($dateTime['minute']) && !empty($dateTime['minute'])) || 
                                                                    (isset($dateTime['second']) && !empty($dateTime['second']))
                                                                   )
       ) return 'year_missing';
    if ((isset($dateTime['year']) && !empty($dateTime['year']) && isset($dateTime['day']) && !empty($dateTime['day'])) &&
        (!isset($dateTime['month']) || empty($dateTime['month']))
       ) return 'month_missing';
    if ((isset($dateTime['year']) && !empty($dateTime['year'])) && 
        ((!isset($dateTime['month']) || empty($dateTime['month'])) || 
         (!isset($dateTime['day']) || empty($dateTime['day']))
        ) &&
        ((isset($dateTime['hour']) && !empty($dateTime['hour'])) ||
         (isset($dateTime['minute']) && !empty($dateTime['minute'])) ||
         (isset($dateTime['second']) && !empty($dateTime['second']))
        )
       ) return 'time_without_date';
    return '';
  }

  public static function getMaskFromDate(array $dateTime)
  {
    $mask = 0;
    if(self::checkDateArray($dateTime)=='')
    {
      foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
      {
        if (isset($dateTime[$key]) && !empty($dateTime[$key]))
        {
          $mask += self::getMaskFor($key);
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
  
  public function getTime($timeFormat=null)
  {
    $timeFormat = (is_null($timeFormat))?$this->getTimeFormat():$timeFormat;
    return $this->format($timeFormat);
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
    return strval($this->getDateTime($this->getWithTime(), $this->getDateFormat(), $this->getTimeFormat()));
  }
  
}
