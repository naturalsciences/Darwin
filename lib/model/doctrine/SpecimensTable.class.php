<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SpecimensTable extends Doctrine_Table
{
    static public $acquisition_category = array(
        'Undefined' => 'Undefined',
        'Donation' => 'Donation',
        'Exchange' => 'Exchange',
        'Internal work' => 'Internal work',
        'Loan' => 'Loan',
        'Mission' => 'Mission',
        'Purchase' => 'Purchase',
        'seizure' => 'Judicial seizure',
        'Trip' => 'Trip',
        'Excavation' => 'Excavation',
        'Exploration' => 'Exploration',
        'Collect' => 'Collect',
        );

    static function getDistinctCategories()
    {
        try{
            $i18n_object = sfContext::getInstance()->getI18n();
        }
        catch( Exception $e )
        {
            return self::$acquisition_category;
        }
        return array_map(array($i18n_object, '__'), self::$acquisition_category);
    }

    public function getDistinctTools()
    {
        $results = Doctrine_Query::create()->
           select('DISTINCT(collecting_tool) as tool')->
           from('Specimens')->
           execute();
        return $results;
    }

    public function getDistinctMethods()
    {
        $results = Doctrine_Query::create()->
           select('DISTINCT(collecting_method) as method')->
           from('Specimens')->
           execute();
        return $results;
    }
}
