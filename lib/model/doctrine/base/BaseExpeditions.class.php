<?php

/**
 * BaseExpeditions
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $name_ts
 * @property string $name_indexed
 * @property string $name_language_full_text
 * @property integer $expedition_from_date_mask
 * @property string $expedition_from_date
 * @property integer $expedition_to_date_mask
 * @property string $expedition_to_date
 * @property Doctrine_Collection $Specimens
 * @property Doctrine_Collection $SpecimenSearch
 * @property Doctrine_Collection $IndividualSearch
 * @property Doctrine_Collection $PartSearch
 * @property Doctrine_Collection $IgsSearch
 * 
 * @method integer             getId()                        Returns the current record's "id" value
 * @method string              getName()                      Returns the current record's "name" value
 * @method string              getNameTs()                    Returns the current record's "name_ts" value
 * @method string              getNameIndexed()               Returns the current record's "name_indexed" value
 * @method string              getNameLanguageFullText()      Returns the current record's "name_language_full_text" value
 * @method integer             getExpeditionFromDateMask()    Returns the current record's "expedition_from_date_mask" value
 * @method string              getExpeditionFromDate()        Returns the current record's "expedition_from_date" value
 * @method integer             getExpeditionToDateMask()      Returns the current record's "expedition_to_date_mask" value
 * @method string              getExpeditionToDate()          Returns the current record's "expedition_to_date" value
 * @method Doctrine_Collection getSpecimens()                 Returns the current record's "Specimens" collection
 * @method Doctrine_Collection getSpecimenSearch()            Returns the current record's "SpecimenSearch" collection
 * @method Doctrine_Collection getIndividualSearch()          Returns the current record's "IndividualSearch" collection
 * @method Doctrine_Collection getPartSearch()                Returns the current record's "PartSearch" collection
 * @method Doctrine_Collection getIgsSearch()                 Returns the current record's "IgsSearch" collection
 * @method Expeditions         setId()                        Sets the current record's "id" value
 * @method Expeditions         setName()                      Sets the current record's "name" value
 * @method Expeditions         setNameTs()                    Sets the current record's "name_ts" value
 * @method Expeditions         setNameIndexed()               Sets the current record's "name_indexed" value
 * @method Expeditions         setNameLanguageFullText()      Sets the current record's "name_language_full_text" value
 * @method Expeditions         setExpeditionFromDateMask()    Sets the current record's "expedition_from_date_mask" value
 * @method Expeditions         setExpeditionFromDate()        Sets the current record's "expedition_from_date" value
 * @method Expeditions         setExpeditionToDateMask()      Sets the current record's "expedition_to_date_mask" value
 * @method Expeditions         setExpeditionToDate()          Sets the current record's "expedition_to_date" value
 * @method Expeditions         setSpecimens()                 Sets the current record's "Specimens" collection
 * @method Expeditions         setSpecimenSearch()            Sets the current record's "SpecimenSearch" collection
 * @method Expeditions         setIndividualSearch()          Sets the current record's "IndividualSearch" collection
 * @method Expeditions         setPartSearch()                Sets the current record's "PartSearch" collection
 * @method Expeditions         setIgsSearch()                 Sets the current record's "IgsSearch" collection
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseExpeditions extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('expeditions');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('name_ts', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('name_indexed', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('name_language_full_text', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('expedition_from_date_mask', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('expedition_from_date', 'string', null, array(
             'type' => 'string',
             'default' => '0001-01-01',
             ));
        $this->hasColumn('expedition_to_date_mask', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('expedition_to_date', 'string', null, array(
             'type' => 'string',
             'default' => '2038-12-31',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Specimens', array(
             'local' => 'id',
             'foreign' => 'expedition_ref'));

        $this->hasMany('SpecimenSearch', array(
             'local' => 'id',
             'foreign' => 'expedition_ref'));

        $this->hasMany('IndividualSearch', array(
             'local' => 'id',
             'foreign' => 'expedition_ref'));

        $this->hasMany('PartSearch', array(
             'local' => 'id',
             'foreign' => 'expedition_ref'));

        $this->hasMany('IgsSearch', array(
             'local' => 'id',
             'foreign' => 'expedition_ref'));
    }
}