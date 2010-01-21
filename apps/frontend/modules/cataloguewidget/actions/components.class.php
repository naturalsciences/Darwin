<?php

/**
 * account actions.
 *
 * @package    darwin
 * @subpackage board_widget
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class cataloguewidgetComponents extends sfComponents
{

  public function executeRelationRename()
  {
    $this->relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table, $this->eid, 'current_name');
  }

  public function executeRelationRecombination()
  {
    $this->relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table, $this->eid, 'recombined from');
  }

  public function executeComment()
  {
    $this->comments =  Doctrine::getTable('Comments')->findForTable($this->table, $this->eid);
    $this->addAllowed = ($this->comments->count() == count(CommentsTable::getNotionsFor($this->table)))?false:true;
  }

  public function executeInsurances()
  {
    $this->insurances =  Doctrine::getTable('Insurances')->findForTable($this->table, $this->eid);
  }

  public function executeProperties()
  {
    $this->properties =  Doctrine::getTable('CatalogueProperties')->findForTable($this->table, $this->eid);
  }

  public function executeSynonym()
  {
    $this->synonyms = Doctrine::getTable('ClassificationSynonymies')->findAllForRecord($this->table, $this->eid);
    //$this->addAllowed = (count(Doctrine::getTable('ClassificationSynonymies')->findGroupsIdsForRecord($this->table, $this->eid)) == count(ClassificationSynonymiesTable::findGroupNames()))?false:true;
    $this->addAllowed = (count(Doctrine::getTable('ClassificationSynonymies')->findGroupsIdsForRecord($this->table, $this->eid)) == 3)?false:true;
  }
}
