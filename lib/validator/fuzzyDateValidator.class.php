<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorDate validates a date. It also converts the input value to a valid date.
 *
 * @package    darwin2
 * @subpackage validator
 * @author     Paul-Andre Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
 * @version    SVN: $Id: sfValidatorDate.class.php 13278 2008-11-23 15:04:24Z FabianLange $
 */
class fuzzyDateValidator extends sfValidatorDate
{
  /**
   * Configures the current validator.
   *
   * Available options from sfValidatorDate:
   *
   *  * date_format:             A regular expression that dates must match
   *  * with_time:               true if the validator must return a time, false otherwise
   *  * date_output:             The format to use when returning a date (default to Y-m-d)
   *  * datetime_output:         The format to use when returning a date with time (default to Y-m-d H:i:s)
   *  * date_format_error:       The date format to use when displaying an error for a bad_format error (use date_format if not provided)
   *  * max:                     The maximum date allowed (as a timestamp)
   *  * min:                     The minimum date allowed (as a timestamp)
   *  * from_date:               true if the date to validate is a begin date, false if an end date in a range
   *  * date_format_range_error: The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)
   *
   * Available error codes:
   *
   *  * bad_format
   *  * min
   *  * max
   *  * year_missing
   *  * month_missing
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorDate
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('year_missing', 'Year missing.');
    $this->addMessage('month_missing', 'Month missing or remove the day and time.');
    $this->addMessage('day_missing', 'Day missing or remove the time.');

    $this->addOption('from_date', true);

    parent::configure($options, $messages);
  }

  /**
   * Give a mask depending on date array entered by user
   *
   * @param array $value An array of date elements
   *
   * @return DateTime A DateTime object
   */
  protected function getMask($value)
  {
    return (!isset($value['year']) || !$value['year'] ? 0 : 32) +
           (!isset($value['month']) || !$value['month'] ? 0 : 16) +
           (!isset($value['day']) || !$value['day'] ? 0 : 8) +
           (!isset($value['hour']) || !$value['hour'] ? 0 : 4) +
           (!isset($value['minute']) || !$value['minute'] ? 0 : 2) +
           (!isset($value['second']) || !$value['second'] ? 0 : 1);
  }

  /**
   * Converts an array representing a date to a timestamp.
   *
   * The array can contains the following keys: year, month, day, hour, minute, second
   *
   * @param  array $value  An array of date elements
   *
   * @return int A Date/Time integer value
   */
  protected function convertDateArray($value)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($value[$key]) && !preg_match('#^\d+$#', $value[$key]) && !empty($value[$key]))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    // Compute the date/timestamp mask
    $empties = $this->getMask($value);
    try {
    if ($empties == 0)
    {
      $value['year'] = ($this->getOption('from_date'))?intval($this->getOption('min')->format('Y')):intval($this->getOption('max')->format('Y'));
      $value['month'] = ($this->getOption('from_date'))?1:12;
      $value['day'] = ($this->getOption('from_date'))?1:31;
      $value['hour'] = ($this->getOption('from_date'))?0:23;
      $value['minute'] = ($this->getOption('from_date'))?0:59;
      $value['second'] = ($this->getOption('from_date'))?0:59;
    }
    else if ($empties <= 31)
    {
      throw new sfValidatorError($this, 'year_missing', array('value' => $value));
    }
    else if ($empties == 32)
    {
      $value['month'] = ($this->getOption('from_date'))?1:12;
      $value['day'] = ($this->getOption('from_date'))?1:31;
      $value['hour'] = ($this->getOption('from_date'))?0:23;
      $value['minute'] = ($this->getOption('from_date'))?0:59;
      $value['second'] = ($this->getOption('from_date'))?0:59;
    }
    else if ($empties <= 47)
    {
      throw new sfValidatorError($this, 'month_missing', array('value' => $value));
    }
    else if ($empties == 48)
    {
      $dateVal = new DateTime(strval(intval($value['year'])).'/'.strval(intval($value['month'])+1).'/0');
      $value['day'] = ($this->getOption('from_date'))?1:intval($dateVal->format('d'));
      $value['hour'] = ($this->getOption('from_date'))?0:23;
      $value['minute'] = ($this->getOption('from_date'))?0:59;
      $value['second'] = ($this->getOption('from_date'))?0:59;
    }
    else if ($empties <= 55)
    {
      throw new sfValidatorError($this, 'day_missing', array('value' => $value));
    }
    

    if (!checkdate(intval($value['month']), intval($value['day']), intval($value['year'])))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if ($this->getOption('with_time'))
    {
      // if second is set, minute and hour must be set
      // if minute is set, hour must be set
      if (
        $this->isValueSet($value, 'second') && (!$this->isValueSet($value, 'minute') || !$this->isValueSet($value, 'hour'))
        ||
        $this->isValueSet($value, 'minute') && !$this->isValueSet($value, 'hour')
      )
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }

      $clean = new DateTime(strval(intval($value['year'])).'/'.
                            strval(intval($value['month'])).'/'.
                            strval(intval($value['day'])).' '.
                            strval(isset($value['hour']) ? intval($value['hour']) : 0).':'.
                            strval(isset($value['minute']) ? intval($value['minute']) : 0).':'.
                            strval(isset($value['second']) ? intval($value['second']) : 0)
                           );
    }
    else
    {
      $clean = new DateTime(strval(intval($value['year'])).'/'.
                            strval(intval($value['month'])).'/'.
                            strval(intval($value['day'])).' 0:0:0'
                           );
    }
    }
    catch (sfValidatorError $e)
    {
      throw $e;
    }
    catch (Exception $e)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    return $clean;
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if (is_array($value))
    {
      $clean = $this->convertDateArray($value);
    }
    else if ($regex = $this->getOption('date_format'))
    {
      if (!preg_match($regex, $value, $match))
      {
        throw new sfValidatorError($this, 'bad_format', array('value' => $value, 'date_format' => $this->getOption('date_format_error') ? $this->getOption('date_format_error') : $this->getOption('date_format')));
      }

      $clean = $this->convertDateArray($match);
    }
    else 
    {
      try 
      {
        $clean = new DateTime($value);
      }
      catch (Exception $e)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    if ($this->hasOption('max') && $clean > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => date($this->getOption('date_format_range_error'), $this->getOption('max'))));
    }

    if ($this->hasOption('min') && $clean < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => date($this->getOption('date_format_range_error'), $this->getOption('min'))));
    }

    $clean = array(($this->getOption('from_date'))?'from_date':'to_date' => $clean, ($this->getOption('from_date'))?'from_date_mask':'to_date_mask' => $this->getMask($value));
    return $clean;

  }
}
