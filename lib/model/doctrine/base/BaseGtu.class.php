<?php

/**
 * BaseGtu
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $code
 * @property integer $parent_ref
 * @property integer $gtu_from_date_mask
 * @property string $gtu_from_date
 * @property integer $gtu_to_date_mask
 * @property string $gtu_to_date
 * @property float $latitude
 * @property float $longitude
 * @property string $location
 * @property float $lat_long_accuracy
 * @property float $elevation
 * @property float $elevation_accuracy
 * @property Gtu $Parent
 * @property Doctrine_Collection $TagGroups
 * @property Doctrine_Collection $Tags
 * @property Doctrine_Collection $Gtu
 * @property Doctrine_Collection $Specimens
 * @property Doctrine_Collection $SpecimenSearch
 * @property Doctrine_Collection $IndividualSearch
 * @property Doctrine_Collection $PartSearch
 * @property Doctrine_Collection $IgsSearch
 * 
 * @method integer             getId()                 Returns the current record's "id" value
 * @method string              getCode()               Returns the current record's "code" value
 * @method integer             getParentRef()          Returns the current record's "parent_ref" value
 * @method integer             getGtuFromDateMask()    Returns the current record's "gtu_from_date_mask" value
 * @method string              getGtuFromDate()        Returns the current record's "gtu_from_date" value
 * @method integer             getGtuToDateMask()      Returns the current record's "gtu_to_date_mask" value
 * @method string              getGtuToDate()          Returns the current record's "gtu_to_date" value
 * @method float               getLatitude()           Returns the current record's "latitude" value
 * @method float               getLongitude()          Returns the current record's "longitude" value
 * @method string              getLocation()           Returns the current record's "location" value
 * @method float               getLatLongAccuracy()    Returns the current record's "lat_long_accuracy" value
 * @method float               getElevation()          Returns the current record's "elevation" value
 * @method float               getElevationAccuracy()  Returns the current record's "elevation_accuracy" value
 * @method Gtu                 getParent()             Returns the current record's "Parent" value
 * @method Doctrine_Collection getTagGroups()          Returns the current record's "TagGroups" collection
 * @method Doctrine_Collection getTags()               Returns the current record's "Tags" collection
 * @method Doctrine_Collection getGtu()                Returns the current record's "Gtu" collection
 * @method Doctrine_Collection getSpecimens()          Returns the current record's "Specimens" collection
 * @method Doctrine_Collection getSpecimenSearch()     Returns the current record's "SpecimenSearch" collection
 * @method Doctrine_Collection getIndividualSearch()   Returns the current record's "IndividualSearch" collection
 * @method Doctrine_Collection getPartSearch()         Returns the current record's "PartSearch" collection
 * @method Doctrine_Collection getIgsSearch()          Returns the current record's "IgsSearch" collection
 * @method Gtu                 setId()                 Sets the current record's "id" value
 * @method Gtu                 setCode()               Sets the current record's "code" value
 * @method Gtu                 setParentRef()          Sets the current record's "parent_ref" value
 * @method Gtu                 setGtuFromDateMask()    Sets the current record's "gtu_from_date_mask" value
 * @method Gtu                 setGtuFromDate()        Sets the current record's "gtu_from_date" value
 * @method Gtu                 setGtuToDateMask()      Sets the current record's "gtu_to_date_mask" value
 * @method Gtu                 setGtuToDate()          Sets the current record's "gtu_to_date" value
 * @method Gtu                 setLatitude()           Sets the current record's "latitude" value
 * @method Gtu                 setLongitude()          Sets the current record's "longitude" value
 * @method Gtu                 setLocation()           Sets the current record's "location" value
 * @method Gtu                 setLatLongAccuracy()    Sets the current record's "lat_long_accuracy" value
 * @method Gtu                 setElevation()          Sets the current record's "elevation" value
 * @method Gtu                 setElevationAccuracy()  Sets the current record's "elevation_accuracy" value
 * @method Gtu                 setParent()             Sets the current record's "Parent" value
 * @method Gtu                 setTagGroups()          Sets the current record's "TagGroups" collection
 * @method Gtu                 setTags()               Sets the current record's "Tags" collection
 * @method Gtu                 setGtu()                Sets the current record's "Gtu" collection
 * @method Gtu                 setSpecimens()          Sets the current record's "Specimens" collection
 * @method Gtu                 setSpecimenSearch()     Sets the current record's "SpecimenSearch" collection
 * @method Gtu                 setIndividualSearch()   Sets the current record's "IndividualSearch" collection
 * @method Gtu                 setPartSearch()         Sets the current record's "PartSearch" collection
 * @method Gtu                 setIgsSearch()          Sets the current record's "IgsSearch" collection
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGtu extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gtu');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('code', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('parent_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('gtu_from_date_mask', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('gtu_from_date', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '0001-01-01',
             ));
        $this->hasColumn('gtu_to_date_mask', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('gtu_to_date', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '2038-12-31',
             ));
        $this->hasColumn('latitude', 'float', null, array(
             'type' => 'float',
             ));
        $this->hasColumn('longitude', 'float', null, array(
             'type' => 'float',
             ));
        $this->hasColumn('location', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('lat_long_accuracy', 'float', null, array(
             'type' => 'float',
             ));
        $this->hasColumn('elevation', 'float', null, array(
             'type' => 'float',
             ));
        $this->hasColumn('elevation_accuracy', 'float', null, array(
             'type' => 'float',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Gtu as Parent', array(
             'local' => 'parent_ref',
             'foreign' => 'id'));

        $this->hasMany('TagGroups', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));

        $this->hasMany('Tags', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));

        $this->hasMany('Gtu', array(
             'local' => 'id',
             'foreign' => 'parent_ref'));

        $this->hasMany('Specimens', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));

        $this->hasMany('SpecimenSearch', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));

        $this->hasMany('IndividualSearch', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));

        $this->hasMany('PartSearch', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));

        $this->hasMany('IgsSearch', array(
             'local' => 'id',
             'foreign' => 'gtu_ref'));
    }
}