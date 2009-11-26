<?php
class Catalogue
{
  public static function getModelForTable($item)
  {
    switch($item)
    {
	  case 'taxonomy' : return 'Taxonomy';
    }
  }
}