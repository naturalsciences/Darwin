<?php

/**
 * BaseSpecimensAccompanying
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $accompanying_type
 * @property integer $specimen_ref
 * @property integer $taxon_ref
 * @property integer $mineral_ref
 * @property string $form
 * @property decimal $quantity
 * @property string $unit
 * @property Specimens $Specimens
 * @property Taxonomy $Taxonomy
 * @property Mineralogy $Mineralogy
 * 
 * @method string                getAccompanyingType()  Returns the current record's "accompanying_type" value
 * @method integer               getSpecimenRef()       Returns the current record's "specimen_ref" value
 * @method integer               getTaxonRef()          Returns the current record's "taxon_ref" value
 * @method integer               getMineralRef()        Returns the current record's "mineral_ref" value
 * @method string                getForm()              Returns the current record's "form" value
 * @method decimal               getQuantity()          Returns the current record's "quantity" value
 * @method string                getUnit()              Returns the current record's "unit" value
 * @method Specimens             getSpecimens()         Returns the current record's "Specimens" value
 * @method Taxonomy              getTaxonomy()          Returns the current record's "Taxonomy" value
 * @method Mineralogy            getMineralogy()        Returns the current record's "Mineralogy" value
 * @method SpecimensAccompanying setAccompanyingType()  Sets the current record's "accompanying_type" value
 * @method SpecimensAccompanying setSpecimenRef()       Sets the current record's "specimen_ref" value
 * @method SpecimensAccompanying setTaxonRef()          Sets the current record's "taxon_ref" value
 * @method SpecimensAccompanying setMineralRef()        Sets the current record's "mineral_ref" value
 * @method SpecimensAccompanying setForm()              Sets the current record's "form" value
 * @method SpecimensAccompanying setQuantity()          Sets the current record's "quantity" value
 * @method SpecimensAccompanying setUnit()              Sets the current record's "unit" value
 * @method SpecimensAccompanying setSpecimens()         Sets the current record's "Specimens" value
 * @method SpecimensAccompanying setTaxonomy()          Sets the current record's "Taxonomy" value
 * @method SpecimensAccompanying setMineralogy()        Sets the current record's "Mineralogy" value
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSpecimensAccompanying extends DarwinModel
{
    public function setTableDefinition()
    {
        $this->setTableName('specimens_accompanying');
        $this->hasColumn('accompanying_type', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'biological',
             ));
        $this->hasColumn('specimen_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('taxon_ref', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('mineral_ref', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('form', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'isolated',
             ));
        $this->hasColumn('quantity', 'decimal', 16, array(
             'type' => 'decimal',
             'length' => 16,
             'scale' => 2,
             ));
        $this->hasColumn('unit', 'string', null, array(
             'type' => 'string',
             'default' => '%',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Specimens', array(
             'local' => 'specimen_ref',
             'foreign' => 'id'));

        $this->hasOne('Taxonomy', array(
             'local' => 'taxon_ref',
             'foreign' => 'id'));

        $this->hasOne('Mineralogy', array(
             'local' => 'mineral_ref',
             'foreign' => 'id'));
    }
}