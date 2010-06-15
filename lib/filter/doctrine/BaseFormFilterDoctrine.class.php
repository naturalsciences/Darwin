<?php

/**
 * Project filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormFilterDoctrine extends sfFormFilterDoctrine
{
  public function setup()
  {
  }

  public function addPagerItems()
  {
    $recPerPages = array("1"=>"1", "2"=>"2", "5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50", "75"=>"75", "100"=>"100");
    
    $this->widgetSchema['rec_per_page'] = new sfWidgetFormChoice(array('choices' => $recPerPages), array('class'=>'rec_per_page'));
    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    $this->widgetSchema->setLabels(array('rec_per_page' => 'Records per page: ',));    
    $this->validatorSchema['rec_per_page'] = new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage'))));
    $this->hasPager = true;
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
      if(isset($this->hasPager) && $this->hasPager)
      {
	if(! isset($taintedValues['rec_per_page']))
	{
	  $taintedValues['rec_per_page'] = $this['rec_per_page']->getValue();
	}
      }
      parent::bind($taintedValues, $taintedFiles);
  }

  protected function getDateItemOptions()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    return array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ); 
  }

  protected function getCatalogueRecLimits()
  {
    return intval(sfConfig::get('app_catalogueRecLimit'));
  }

  /**
  * Add query for table containing "name" and "name_indexed" columns
  * @param Doctrine_Query $query an existing doctrine query
  * @param string $table a table where we should look at
  * @param string $field The field searched
  * @param string $values The value of the search field
  * @return Doctrine_Query the modified doctrine query
  */
  public function addNamingColumnQuery(Doctrine_Query $query, $table, $field, $values)
  {
	if ($values != "" && $table != "" && $field != "")
	{
	  $alias = $query->getRootAlias();
	  $search = self::splitNameQuery($values);
	  $terms = self::getAllTerms($search);
	  if(empty($terms)) return $query;
	  
	  $conn_MGR = Doctrine_Manager::connection();
	  $conn = $conn_MGR->getDbh();
	  $pg_array_string  = '';
	  foreach($terms as $term)
		$pg_array_string .= $conn_MGR->quote($term, 'string').',';

	  $pg_array_string = substr($pg_array_string, 0, -1); //remove last ','

	  $statement = $conn->prepare("SELECT vt.anyelement as search, word from fct_explode_array(array[$pg_array_string]) as vt  
		LEFT JOIN words on word % vt.anyelement
		WHERE referenced_relation = :table
		AND field_name = :field
		AND word ilike '%' || vt.anyelement || '%'
		GROUP BY word,  vt.anyelement");

	  $statement->execute(array(':table' => $table, ':field' => $field));
	  $results = $statement->fetchAll(PDO::FETCH_ASSOC);

	  //print_r($results);
	  if(count($results) == 0) return $query;

	  foreach ($search['with'] as $search_term)
	  {
		$tsquery =  self::getWordsForTerms($search_term, $results);
		$query->andWhere($alias.'.'.$field." @@ to_tsquery('simple',?) ",$tsquery);
	  }

	  unset($search['with']);
	  foreach($search as $search_group)
	  {
		$tsquery_arr = array();
		foreach($search_group as $search_term)
		{
		  $tsquery_arr[] =  self::getWordsForTerms($search_term, $results);
		}
		$tsquery = implode(' & ',$tsquery_arr);
		$tsquery = $conn_MGR->quote($tsquery,'string');
		$query->andWhere('not '.$alias.'.'.$field." @@ to_tsquery('simple',".$tsquery.") ");
	  }
	  
	}
	return $query;
  }

  /**
  * Get a searched term and give a string containing all proposition separeted by $separator 
  * @param string $term the searched term
  * @param string $proposition the array of propositions fetched in word db
  * @param string $separator the separator between fields
  * @return a string containing all proposition for this term separeted by $separator
  */
  protected static function getWordsForTerms($term, $propositions, $separator = '|' )
  {
	$str = '';
	foreach($propositions as $i => $result)
	{
	  if($result['search'] == $term)
	  {
		if($str == '')
		  $str = $result['word'];
		else
		  $str .= $separator.$result['word'];
	  }
	}
	if($str == '') $str = $term;
	return $str;
  }

  /**
  * splitNameQuery: Analyse a query string to split elements 
  * in remove all non-alphanum char (except - and _ ) 
  * The array si organized with a 'with' key (all search terms) and other keys represent a 'NOT' search
  * Terms will be trimed and -- is used as not term
  * falco, peregrin (1985) --brolus test -- choze will be exported as:
  * Array ( [with] => Array(
  *          [0] => falco
  *          [1] => peregrin
  *          [2] => 1985)
  *   [0] => Array(
  *          [0] => brolus
  *          [1] => test)
  *   [1] => Array(
  *          [0] => choze)
  * )
  *
  * @param string $query_string The search string
  * @return array an array of analysed terms
  */
  public static function splitNameQuery($query_string)
  {
	$results = preg_split('/--/',$query_string);
	$query_array = array('with'=>'', 'without'=>array());
	foreach($results as $i => $query_part)
	{
	  if(trim($query_part) == '')
		continue;
	  
	  $query_part = preg_replace('/[^A-Za-z0-9\-_]/', ' ', $query_part);

	  if($i == 0)
		$query_array['with'] = trim($query_part);
	  else
		$query_array['without'][] = trim($query_part);
	}

	$searched_terms = array();
	foreach(array_merge(array('with'=>$query_array['with']),$query_array['without']) as $i => $query_words)
	{
	  $words = explode(" ", $query_words);
	  $searched_terms[$i] = array();
	  foreach($words as $word)
	  {
		$word = trim($word);
		
		if($word == '') continue;
		
		$searched_terms[$i][] = $word;
	  }
	  if(empty($searched_terms[$i]))
		unset($searched_terms[$i]);
	}
	if(!isset($searched_terms['with']))
	  $searched_terms['with'] = array();

	return $searched_terms;
  }

  /**
  * Extract query items from the analysed array
  * @param array analysed array with key with and without and query splitted in terms
  * @return array array with all terms searched
  */
  protected static function getAllTerms($analysed_array)
  {
	$terms = array();
	foreach($analysed_array as $key => $analysed)
	{
	  foreach($analysed as $query)
	  {
		$terms[] = $query;
	  }
	}
	return $terms;
  }

  public function addDateFromToColumnQuery(Doctrine_Query $query, array $dateFields, $val_from, $val_to)
  {
    if (count($dateFields) > 0)
    {
      if($val_from->getMask() > 0 && $val_to->getMask() > 0)
      {
        if (count($dateFields) == 1)
        {
          $query->andWhere($dateFields[0] . " Between ? and ? ",
                           array($val_from->format('d/m/Y'), 
                                 $val_to->format('d/m/Y')
                                )
                          );
        }
        else
        {
          $query->andWhere(" " . $dateFields[0] . " >= ? ", $val_from->format('d/m/Y'))
                ->andWhere(" " . $dateFields[1] . " <= ? ", $val_to->format('d/m/Y'));
        }
      }
      elseif ($val_from->getMask() > 0)
      {
        $sql = " (" . $dateFields[0] . " >= ? AND " . $dateFields[0] . "_mask > 0) ";
        for ($i = 1; $i <= count($dateFields); $i++)
        {
          $vals[] = $val_from->format('d/m/Y');
        }
        if (count($dateFields) > 1) $sql .= " OR (" . $dateFields[1] . " >= ? AND " . $dateFields[1] . "_mask > 0) ";
        $query->andWhere($sql, 
                         $vals
                        );
      } 
      elseif ($val_to->getMask() > 0)
      {
        $sql = " (" . $dateFields[0] . " <= ? AND " . $dateFields[0] . "_mask > 0) ";
        for ($i = 1; $i <= count($dateFields); $i++)
        {
          $vals[] = $val_to->format('d/m/Y');
        }
        if (count($dateFields) > 1) $sql .= " OR (" . $dateFields[1] . " <= ? AND " . $dateFields[1] . "_mask > 0) ";
        $query->andWhere($sql, 
                         $vals
                        );
      }
    }
    return $query;
  }

  public function addLevelColumnQuery(Doctrine_Query $query, $field, $values)
  {
     if ($values != "")
     {
       $levels = array();
       $pul = Doctrine::getTable('PossibleUpperLevels')->findByLevelRef($values)->toArray();
       foreach ($pul as $key=>$val)
       {
         $levels[]=$val['level_upper_ref'];
       }
       if (count($levels)>0):     
         $query->andWhereIn('level_ref', $levels);
       endif;
     }
     return $query;
  }

  public function addCallerIdColumnQuery(Doctrine_Query $query, $field, $values)
  {
     if ($values != "")
     {
       $alias = $query->getRootAlias();       
       $query->andWhere($alias.'.id != ?', $values);
     }
     return $query;
  }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function getCurrentCulture()
  {
    return isset($this->options['culture']) ? $this->options['culture'] : 'en';
  }

  public function getJavascripts()
  {
    $javascripts=parent::getJavascripts();
    $javascripts[]='/js/searchForm.js';
    $javascripts[]='/js/pager.js';
    return $javascripts;
  }

}
