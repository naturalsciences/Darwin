<?php

/**
 * BaseUsers
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property boolean $is_physical
 * @property string $sub_type
 * @property string $formated_name
 * @property string $formated_name_indexed
 * @property string $formated_name_ts
 * @property string $title
 * @property string $family_name
 * @property string $given_name
 * @property string $additional_names
 * @property integer $birth_date_mask
 * @property string $birth_date
 * @property enum $gender
 * @property integer $db_user_type
 * @property integer $people_id
 * @property People $People
 * @property Doctrine_Collection $UsersLanguages
 * @property Doctrine_Collection $UsersComm
 * @property Doctrine_Collection $UsersAddresses
 * @property Doctrine_Collection $UsersLoginInfos
 * @property Doctrine_Collection $Collections
 * @property Doctrine_Collection $CollectionsRights
 * @property Doctrine_Collection $UsersTracking
 * @property Doctrine_Collection $MySavedSearches
 * @property Doctrine_Collection $MyWidgets
 * @property Doctrine_Collection $Preferences
 * @property Doctrine_Collection $SpecimenSearch
 * @property Doctrine_Collection $IndividualSearch
 * @property Doctrine_Collection $PartSearch
 * 
 * @method integer             getId()                    Returns the current record's "id" value
 * @method boolean             getIsPhysical()            Returns the current record's "is_physical" value
 * @method string              getSubType()               Returns the current record's "sub_type" value
 * @method string              getFormatedName()          Returns the current record's "formated_name" value
 * @method string              getFormatedNameIndexed()   Returns the current record's "formated_name_indexed" value
 * @method string              getFormatedNameTs()        Returns the current record's "formated_name_ts" value
 * @method string              getTitle()                 Returns the current record's "title" value
 * @method string              getFamilyName()            Returns the current record's "family_name" value
 * @method string              getGivenName()             Returns the current record's "given_name" value
 * @method string              getAdditionalNames()       Returns the current record's "additional_names" value
 * @method integer             getBirthDateMask()         Returns the current record's "birth_date_mask" value
 * @method string              getBirthDate()             Returns the current record's "birth_date" value
 * @method enum                getGender()                Returns the current record's "gender" value
 * @method integer             getDbUserType()            Returns the current record's "db_user_type" value
 * @method integer             getPeopleId()              Returns the current record's "people_id" value
 * @method People              getPeople()                Returns the current record's "People" value
 * @method Doctrine_Collection getUsersLanguages()        Returns the current record's "UsersLanguages" collection
 * @method Doctrine_Collection getUsersComm()             Returns the current record's "UsersComm" collection
 * @method Doctrine_Collection getUsersAddresses()        Returns the current record's "UsersAddresses" collection
 * @method Doctrine_Collection getUsersLoginInfos()       Returns the current record's "UsersLoginInfos" collection
 * @method Doctrine_Collection getCollections()           Returns the current record's "Collections" collection
 * @method Doctrine_Collection getCollectionsRights()     Returns the current record's "CollectionsRights" collection
 * @method Doctrine_Collection getUsersTracking()         Returns the current record's "UsersTracking" collection
 * @method Doctrine_Collection getMySavedSearches()       Returns the current record's "MySavedSearches" collection
 * @method Doctrine_Collection getMyWidgets()             Returns the current record's "MyWidgets" collection
 * @method Doctrine_Collection getPreferences()           Returns the current record's "Preferences" collection
 * @method Doctrine_Collection getSpecimenSearch()        Returns the current record's "SpecimenSearch" collection
 * @method Doctrine_Collection getIndividualSearch()      Returns the current record's "IndividualSearch" collection
 * @method Doctrine_Collection getPartSearch()            Returns the current record's "PartSearch" collection
 * @method Users               setId()                    Sets the current record's "id" value
 * @method Users               setIsPhysical()            Sets the current record's "is_physical" value
 * @method Users               setSubType()               Sets the current record's "sub_type" value
 * @method Users               setFormatedName()          Sets the current record's "formated_name" value
 * @method Users               setFormatedNameIndexed()   Sets the current record's "formated_name_indexed" value
 * @method Users               setFormatedNameTs()        Sets the current record's "formated_name_ts" value
 * @method Users               setTitle()                 Sets the current record's "title" value
 * @method Users               setFamilyName()            Sets the current record's "family_name" value
 * @method Users               setGivenName()             Sets the current record's "given_name" value
 * @method Users               setAdditionalNames()       Sets the current record's "additional_names" value
 * @method Users               setBirthDateMask()         Sets the current record's "birth_date_mask" value
 * @method Users               setBirthDate()             Sets the current record's "birth_date" value
 * @method Users               setGender()                Sets the current record's "gender" value
 * @method Users               setDbUserType()            Sets the current record's "db_user_type" value
 * @method Users               setPeopleId()              Sets the current record's "people_id" value
 * @method Users               setPeople()                Sets the current record's "People" value
 * @method Users               setUsersLanguages()        Sets the current record's "UsersLanguages" collection
 * @method Users               setUsersComm()             Sets the current record's "UsersComm" collection
 * @method Users               setUsersAddresses()        Sets the current record's "UsersAddresses" collection
 * @method Users               setUsersLoginInfos()       Sets the current record's "UsersLoginInfos" collection
 * @method Users               setCollections()           Sets the current record's "Collections" collection
 * @method Users               setCollectionsRights()     Sets the current record's "CollectionsRights" collection
 * @method Users               setUsersTracking()         Sets the current record's "UsersTracking" collection
 * @method Users               setMySavedSearches()       Sets the current record's "MySavedSearches" collection
 * @method Users               setMyWidgets()             Sets the current record's "MyWidgets" collection
 * @method Users               setPreferences()           Sets the current record's "Preferences" collection
 * @method Users               setSpecimenSearch()        Sets the current record's "SpecimenSearch" collection
 * @method Users               setIndividualSearch()      Sets the current record's "IndividualSearch" collection
 * @method Users               setPartSearch()            Sets the current record's "PartSearch" collection
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUsers extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('users');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('is_physical', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             ));
        $this->hasColumn('sub_type', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('formated_name', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('formated_name_indexed', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('formated_name_ts', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('title', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('family_name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('given_name', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('additional_names', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('birth_date_mask', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('birth_date', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '0001-01-01',
             ));
        $this->hasColumn('gender', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'M',
              1 => 'F',
             ),
             ));
        $this->hasColumn('db_user_type', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('people_id', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('People', array(
             'local' => 'people_id',
             'foreign' => 'id'));

        $this->hasMany('UsersLanguages', array(
             'local' => 'id',
             'foreign' => 'users_ref'));

        $this->hasMany('UsersComm', array(
             'local' => 'id',
             'foreign' => 'person_user_ref'));

        $this->hasMany('UsersAddresses', array(
             'local' => 'id',
             'foreign' => 'person_user_ref'));

        $this->hasMany('UsersLoginInfos', array(
             'local' => 'id',
             'foreign' => 'user_ref'));

        $this->hasMany('Collections', array(
             'local' => 'id',
             'foreign' => 'main_manager_ref'));

        $this->hasMany('CollectionsRights', array(
             'local' => 'id',
             'foreign' => 'user_ref'));

        $this->hasMany('UsersTracking', array(
             'local' => 'id',
             'foreign' => 'user_ref'));

        $this->hasMany('MySavedSearches', array(
             'local' => 'id',
             'foreign' => 'user_ref'));

        $this->hasMany('MyWidgets', array(
             'local' => 'id',
             'foreign' => 'user_ref'));

        $this->hasMany('Preferences', array(
             'local' => 'id',
             'foreign' => 'user_ref'));

        $this->hasMany('SpecimenSearch', array(
             'local' => 'id',
             'foreign' => 'collection_main_manager_ref'));

        $this->hasMany('IndividualSearch', array(
             'local' => 'id',
             'foreign' => 'collection_main_manager_ref'));

        $this->hasMany('PartSearch', array(
             'local' => 'id',
             'foreign' => 'collection_main_manager_ref'));
    }
}