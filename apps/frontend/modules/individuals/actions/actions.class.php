<?php

/**
 * Individuals actions.
 *
 * @package    darwin
 * @subpackage individuals
 * @author     DB team <collections@naturalsciences.be>
 */
class individualsActions extends DarwinActions
{
  public function executeEdit(sfWebRequest $request)
  {
	$this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id'));
	$this->individuals = Doctrine_Query::create()
            ->from('SpecimenIndividuals')->where('specimen_ref = ?', $this->specimen->getId())->execute();
  }
}
