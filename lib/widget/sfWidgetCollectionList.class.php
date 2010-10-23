<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetDarwinList represents a choice widget for a model.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfWidgetFormDoctrineChoice.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
 
class sfWidgetCollectionList extends sfWidgetFormChoice
{
  public function configure($options = array(),$attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('multiple', true);
    $this->addOption('extended', true);
  }

  private function getCollectionByIntitution()
  {
    $tab = array() ;
    $user=null;
    $only_public = true;
    if(! $this->hasOption('public_only') || $this->getOption('public_only')==false )
    {
      $user = sfContext::getInstance()->getUser();
      $only_public = false;
    }
    $objects = Doctrine_Core::getTable('Collections')->fetchByInstitutionList($user, null, $only_public) ;  
    foreach($objects as $institution)
    {
      $tab[$institution->getFormatedName()] = array() ;
      foreach($institution->Collections as $collection)
       $tab[$institution->getFormatedName()][] = $collection ;
    } 
    return $tab ;
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('multiple'))
    {
      $attributes['multiple'] = 'multiple';

      if ('[]' != substr($name, -2))
      {
        $name .= '[]';
      }
    }
    $options = array();

    $institutions = $this->getCollectionByIntitution() ;
    $html = "" ;
    $prev_level = 0 ;
    foreach($institutions as $institution=> $collections)
    {
        $html .= "<h2>$institution</h2>" ;
        $html .= "<div class=\"treelist\">" ;
        $html .= $this->getChoiceRenderer($value, $name, $collections ) ;
        $html .= "</div>" ;
    }
    return($html) ;
  }
  
  protected function getChoiceRenderer($value, $name, $collections = null)
  {
    $html = "" ;
    $prev_level = 0 ;
    $img_expand = 'blue_expand.png';
    $img_expand_up = 'blue_expand_up.png' ;
    foreach ($collections as $val)
    {
        if($prev_level < $val->getLevel())
          $html .= "<ul>\n" ;
        else
        {
          $html .= "</li>\n" ;
          if($prev_level > $val->getLevel())
              $html .= str_repeat('</ul></li>',$prev_level-$val->getLevel());
        }
        $html .= "<li class=\"rid_".$val."\"><div class=\"col_name\">" ;
        $html .= image_tag ($img_expand, array('alt' => '+', 'class'=> 'tree_cmd collapsed'));
        $html .= image_tag ($img_expand_up, array('alt' => '-', 'class'=> 'tree_cmd expanded'));
        $html .=  "<span>".$val->getName()."</span>";

        $options = array(
          'type'=> 'checkbox',
          'class' => 'check_right',
          'value' => $val->getId(),
          'name' => $name,
        );
        if(is_array($value) && in_array($val->getId(), $value) )
          $options['checked'] = "checked";
        $html .= $this->renderTag('input', $options);

        $html .= "</div>" ;
        $prev_level = $val->getLevel();
    }
    $html .= str_repeat('</li></ul>',$val->getLevel());       
    return $html ;
  }
}
