<?php

/**
 * BaseInsurances
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $referenced_relation
 * @property integer $record_id
 * @property decimal $insurance_value
 * @property string $insurance_currency
 * @property integer $insurance_year
 * @property integer $insurer_ref
 * @property People $People
 * 
 * @method integer    getId()                  Returns the current record's "id" value
 * @method string     getReferencedRelation()  Returns the current record's "referenced_relation" value
 * @method integer    getRecordId()            Returns the current record's "record_id" value
 * @method decimal    getInsuranceValue()      Returns the current record's "insurance_value" value
 * @method string     getInsuranceCurrency()   Returns the current record's "insurance_currency" value
 * @method integer    getInsuranceYear()       Returns the current record's "insurance_year" value
 * @method integer    getInsurerRef()          Returns the current record's "insurer_ref" value
 * @method People     getPeople()              Returns the current record's "People" value
 * @method Insurances setId()                  Sets the current record's "id" value
 * @method Insurances setReferencedRelation()  Sets the current record's "referenced_relation" value
 * @method Insurances setRecordId()            Sets the current record's "record_id" value
 * @method Insurances setInsuranceValue()      Sets the current record's "insurance_value" value
 * @method Insurances setInsuranceCurrency()   Sets the current record's "insurance_currency" value
 * @method Insurances setInsuranceYear()       Sets the current record's "insurance_year" value
 * @method Insurances setInsurerRef()          Sets the current record's "insurer_ref" value
 * @method Insurances setPeople()              Sets the current record's "People" value
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseInsurances extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('insurances');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('referenced_relation', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('record_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('insurance_value', 'decimal', 16, array(
             'type' => 'decimal',
             'length' => 16,
             'scale' => 2,
             'notnull' => true,
             ));
        $this->hasColumn('insurance_currency', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '€',
             ));
        $this->hasColumn('insurance_year', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('insurer_ref', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('People', array(
             'local' => 'insurer_ref',
             'foreign' => 'id'));
    }
}