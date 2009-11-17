<?php

/**
 * Taxonomy form.
 *
 * @package    form
 * @subpackage Taxonomy
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class TaxonomyForm extends BaseTaxonomyForm
{
  public function configure()
  {
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => array($this->getI18N()->__('valid'), $this->getI18N()->__('invalid'), $this->getI18N()->__('depracated')),
    ));
    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
	'model' => 'CatalogueLevels',
	'table_method' => 'getLevelsForTaxo',
	'add_empty' => true
      ));
    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Taxonomy',
       'method' => 'getName',
       'link_url' => 'taxonomy/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
     ));


    $this->embedForm('recombination_1', new SpecimensRelationshipsForm());
    $this->embedForm('recombination_2', new SpecimensRelationshipsForm());
    $this->embedForm('current_name', new SpecimensRelationshipsForm());
  }

  public function loadRelationsForms($relations)
  {
    $combination = false;
    foreach($relations as $relation)
    {
	$s_form = new SpecimensRelationshipsForm($relation);
	$s_form->setDefault('enabled',true);

	if($relation->getRelationshipType()=='current taxon')
	{
	    $this->embedForm('current_name', $s_form);
	}
	elseif($relation->getRelationshipType()=='recombined from' && ! $combination )
	{
	    $this->embedForm('recombination_1', $s_form);
	    $combination=true;
	}
	else
	{
	  $this->embedForm('recombination_2', $s_form);
	}
    }
  }
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (is_null($con))
    {
      $con = $this->getConnection();
    }
 
    if (is_null($forms))
    {
      $forms = $this->embeddedForms;
    }
 
    foreach ($forms as $key=>$form)
    {
      if ($form instanceof sfFormDoctrine)
      {
	if($form instanceof SpecimensRelationshipsForm)
	{
	  $form->getObject()->setReferencedRelation($this->getObject()->getTable()->getTableName());
	  $form->getObject()->setrecord_id_1($this->getObject()->getId());
	  if($key=='current_name')
	    $form->getObject()->setRelationshipType('current taxon');
	  if($form->getObject()->getrecord_id_2()==null)
	    continue;
	}
        // Here it ends
        $form->getObject()->save($con);
        $form->saveEmbeddedForms($con);
      }
      else
      {
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
      }
    }
  }
}