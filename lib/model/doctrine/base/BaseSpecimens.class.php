<?php

/**
 * BaseSpecimens
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $collection_ref
 * @property integer $expedition_ref
 * @property integer $gtu_ref
 * @property integer $taxon_ref
 * @property integer $litho_ref
 * @property integer $chrono_ref
 * @property integer $lithology_ref
 * @property integer $mineral_ref
 * @property integer $host_taxon_ref
 * @property integer $host_specimen_ref
 * @property string $host_relationship
 * @property string $acquisition_category
 * @property integer $acquisition_date_mask
 * @property string $acquisition_date
 * @property string $collecting_method
 * @property string $collecting_tool
 * @property integer $specimen_count_min
 * @property integer $specimen_count_max
 * @property boolean $station_visible
 * @property boolean $multimedia_visible
 * @property integer $ig_ref
 * @property Collections $Collections
 * @property Expeditions $Expeditions
 * @property Taxonomy $Taxonomy
 * @property Lithostratigraphy $Lithostratigraphy
 * @property Chronostratigraphy $Chronostratigraphy
 * @property Lithology $Lithology
 * @property Mineralogy $Mineralogy
 * @property Taxonomy $IdentificationsTaxon
 * @property Taxonomy $HostTaxon
 * @property Specimens $HostSpecimen
 * @property Igs $Igs
 * @property Doctrine_Collection $Specimens
 * @property Doctrine_Collection $SpecimenIndividuals
 * @property Doctrine_Collection $SpecimensAccompanying
 * 
 * @method integer             getId()                    Returns the current record's "id" value
 * @method integer             getCollectionRef()         Returns the current record's "collection_ref" value
 * @method integer             getExpeditionRef()         Returns the current record's "expedition_ref" value
 * @method integer             getGtuRef()                Returns the current record's "gtu_ref" value
 * @method integer             getTaxonRef()              Returns the current record's "taxon_ref" value
 * @method integer             getLithoRef()              Returns the current record's "litho_ref" value
 * @method integer             getChronoRef()             Returns the current record's "chrono_ref" value
 * @method integer             getLithologyRef()          Returns the current record's "lithology_ref" value
 * @method integer             getMineralRef()            Returns the current record's "mineral_ref" value
 * @method integer             getHostTaxonRef()          Returns the current record's "host_taxon_ref" value
 * @method integer             getHostSpecimenRef()       Returns the current record's "host_specimen_ref" value
 * @method string              getHostRelationship()      Returns the current record's "host_relationship" value
 * @method string              getAcquisitionCategory()   Returns the current record's "acquisition_category" value
 * @method integer             getAcquisitionDateMask()   Returns the current record's "acquisition_date_mask" value
 * @method string              getAcquisitionDate()       Returns the current record's "acquisition_date" value
 * @method string              getCollectingMethod()      Returns the current record's "collecting_method" value
 * @method string              getCollectingTool()        Returns the current record's "collecting_tool" value
 * @method integer             getSpecimenCountMin()      Returns the current record's "specimen_count_min" value
 * @method integer             getSpecimenCountMax()      Returns the current record's "specimen_count_max" value
 * @method boolean             getStationVisible()        Returns the current record's "station_visible" value
 * @method boolean             getMultimediaVisible()     Returns the current record's "multimedia_visible" value
 * @method integer             getIgRef()                 Returns the current record's "ig_ref" value
 * @method Collections         getCollections()           Returns the current record's "Collections" value
 * @method Expeditions         getExpeditions()           Returns the current record's "Expeditions" value
 * @method Taxonomy            getTaxonomy()              Returns the current record's "Taxonomy" value
 * @method Lithostratigraphy   getLithostratigraphy()     Returns the current record's "Lithostratigraphy" value
 * @method Chronostratigraphy  getChronostratigraphy()    Returns the current record's "Chronostratigraphy" value
 * @method Lithology           getLithology()             Returns the current record's "Lithology" value
 * @method Mineralogy          getMineralogy()            Returns the current record's "Mineralogy" value
 * @method Taxonomy            getIdentificationsTaxon()  Returns the current record's "IdentificationsTaxon" value
 * @method Taxonomy            getHostTaxon()             Returns the current record's "HostTaxon" value
 * @method Specimens           getHostSpecimen()          Returns the current record's "HostSpecimen" value
 * @method Igs                 getIgs()                   Returns the current record's "Igs" value
 * @method Doctrine_Collection getSpecimens()             Returns the current record's "Specimens" collection
 * @method Doctrine_Collection getSpecimenIndividuals()   Returns the current record's "SpecimenIndividuals" collection
 * @method Doctrine_Collection getSpecimensAccompanying() Returns the current record's "SpecimensAccompanying" collection
 * @method Specimens           setId()                    Sets the current record's "id" value
 * @method Specimens           setCollectionRef()         Sets the current record's "collection_ref" value
 * @method Specimens           setExpeditionRef()         Sets the current record's "expedition_ref" value
 * @method Specimens           setGtuRef()                Sets the current record's "gtu_ref" value
 * @method Specimens           setTaxonRef()              Sets the current record's "taxon_ref" value
 * @method Specimens           setLithoRef()              Sets the current record's "litho_ref" value
 * @method Specimens           setChronoRef()             Sets the current record's "chrono_ref" value
 * @method Specimens           setLithologyRef()          Sets the current record's "lithology_ref" value
 * @method Specimens           setMineralRef()            Sets the current record's "mineral_ref" value
 * @method Specimens           setHostTaxonRef()          Sets the current record's "host_taxon_ref" value
 * @method Specimens           setHostSpecimenRef()       Sets the current record's "host_specimen_ref" value
 * @method Specimens           setHostRelationship()      Sets the current record's "host_relationship" value
 * @method Specimens           setAcquisitionCategory()   Sets the current record's "acquisition_category" value
 * @method Specimens           setAcquisitionDateMask()   Sets the current record's "acquisition_date_mask" value
 * @method Specimens           setAcquisitionDate()       Sets the current record's "acquisition_date" value
 * @method Specimens           setCollectingMethod()      Sets the current record's "collecting_method" value
 * @method Specimens           setCollectingTool()        Sets the current record's "collecting_tool" value
 * @method Specimens           setSpecimenCountMin()      Sets the current record's "specimen_count_min" value
 * @method Specimens           setSpecimenCountMax()      Sets the current record's "specimen_count_max" value
 * @method Specimens           setStationVisible()        Sets the current record's "station_visible" value
 * @method Specimens           setMultimediaVisible()     Sets the current record's "multimedia_visible" value
 * @method Specimens           setIgRef()                 Sets the current record's "ig_ref" value
 * @method Specimens           setCollections()           Sets the current record's "Collections" value
 * @method Specimens           setExpeditions()           Sets the current record's "Expeditions" value
 * @method Specimens           setTaxonomy()              Sets the current record's "Taxonomy" value
 * @method Specimens           setLithostratigraphy()     Sets the current record's "Lithostratigraphy" value
 * @method Specimens           setChronostratigraphy()    Sets the current record's "Chronostratigraphy" value
 * @method Specimens           setLithology()             Sets the current record's "Lithology" value
 * @method Specimens           setMineralogy()            Sets the current record's "Mineralogy" value
 * @method Specimens           setIdentificationsTaxon()  Sets the current record's "IdentificationsTaxon" value
 * @method Specimens           setHostTaxon()             Sets the current record's "HostTaxon" value
 * @method Specimens           setHostSpecimen()          Sets the current record's "HostSpecimen" value
 * @method Specimens           setIgs()                   Sets the current record's "Igs" value
 * @method Specimens           setSpecimens()             Sets the current record's "Specimens" collection
 * @method Specimens           setSpecimenIndividuals()   Sets the current record's "SpecimenIndividuals" collection
 * @method Specimens           setSpecimensAccompanying() Sets the current record's "SpecimensAccompanying" collection
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7380 2010-03-15 21:07:50Z jwage $
 */
abstract class BaseSpecimens extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('specimens');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('collection_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('expedition_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('gtu_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('taxon_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('litho_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('chrono_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('lithology_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('mineral_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('host_taxon_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('host_specimen_ref', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('host_relationship', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('acquisition_category', 'string', null, array(
             'type' => 'string',
             'default' => 'expedition',
             ));
        $this->hasColumn('acquisition_date_mask', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('acquisition_date', 'string', null, array(
             'type' => 'string',
             'default' => '0001-01-01',
             ));
        $this->hasColumn('collecting_method', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('collecting_tool', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('specimen_count_min', 'integer', null, array(
             'type' => 'integer',
             'default' => 1,
             ));
        $this->hasColumn('specimen_count_max', 'integer', null, array(
             'type' => 'integer',
             'default' => 1,
             ));
        $this->hasColumn('station_visible', 'boolean', null, array(
             'type' => 'boolean',
             'default' => true,
             ));
        $this->hasColumn('multimedia_visible', 'boolean', null, array(
             'type' => 'boolean',
             'default' => true,
             ));
        $this->hasColumn('ig_ref', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Collections', array(
             'local' => 'collection_ref',
             'foreign' => 'id'));

        $this->hasOne('Expeditions', array(
             'local' => 'expedition_ref',
             'foreign' => 'id'));

        $this->hasOne('Taxonomy', array(
             'local' => 'taxon_ref',
             'foreign' => 'id'));

        $this->hasOne('Lithostratigraphy', array(
             'local' => 'litho_ref',
             'foreign' => 'id'));

        $this->hasOne('Chronostratigraphy', array(
             'local' => 'chrono_ref',
             'foreign' => 'id'));

        $this->hasOne('Lithology', array(
             'local' => 'lithology_ref',
             'foreign' => 'id'));

        $this->hasOne('Mineralogy', array(
             'local' => 'mineral_ref',
             'foreign' => 'id'));

        $this->hasOne('Taxonomy as IdentificationsTaxon', array(
             'local' => 'identification_taxon_ref',
             'foreign' => 'id'));

        $this->hasOne('Taxonomy as HostTaxon', array(
             'local' => 'host_taxon_ref',
             'foreign' => 'id'));

        $this->hasOne('Specimens as HostSpecimen', array(
             'local' => 'host_specimen_ref',
             'foreign' => 'id'));

        $this->hasOne('Igs', array(
             'local' => 'ig_ref',
             'foreign' => 'id'));

        $this->hasMany('Specimens', array(
             'local' => 'id',
             'foreign' => 'host_specimen_ref'));

        $this->hasMany('SpecimenIndividuals', array(
             'local' => 'id',
             'foreign' => 'specimen_ref'));

        $this->hasMany('SpecimensAccompanying', array(
             'local' => 'id',
             'foreign' => 'specimen_ref'));
    }
}