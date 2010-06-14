<?php

class DarwinTestFunctional extends sfTestFunctional
{
  public function loadData($configuration)
  {
    new sfDatabaseManager($configuration);
    self::initiateDB($configuration);
    Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');
    return $this;
  }

  public static function initiateDB()
  {
    $conn = Doctrine_Manager::connection();
    $conn->exec("SELECT nextval('taxonomy_id_seq')");
    $conn->exec("SELECT setval('taxonomy_id_seq',10000)");
    $conn->exec("SELECT nextval('chronostratigraphy_id_seq')");
    $conn->exec("SELECT setval('chronostratigraphy_id_seq',10000)");
    $conn->exec("SELECT nextval('lithostratigraphy_id_seq')");
    $conn->exec("SELECT setval('lithostratigraphy_id_seq',10000)");
    $conn->exec("SELECT nextval('mineralogy_id_seq')");
    $conn->exec("SELECT setval('mineralogy_id_seq',10000)");
    $conn->exec("SELECT nextval('lithology_id_seq')");
    $conn->exec("SELECT setval('lithology_id_seq',10000)");
    $conn->exec("SELECT nextval('expeditions_id_seq')");
    $conn->exec("SELECT setval('expeditions_id_seq',10000)");
    $conn->exec("SELECT nextval('classification_synonymies_id_seq')");
    $conn->exec("SELECT setval('classification_synonymies_id_seq',1)");
    $conn->exec("SELECT nextval('gtu_id_seq')");
    $conn->exec("SELECT setval('gtu_id_seq',1)");
    $conn->exec("SELECT nextval('identifications_id_seq')");
    $conn->exec("SELECT setval('identifications_id_seq',10000)");
    $conn->exec("SELECT nextval('specimens_id_seq')");
    $conn->exec("SELECT setval('specimens_id_seq',100)");
    $conn->exec("SELECT nextval('catalogue_people_id_seq')");
    $conn->exec("SELECT setval('catalogue_people_id_seq',100)");

  }

  public function login($user,$pass)
  {
    $this->
      info('** initial login **')->
      get('account/login')->
      click('#login_page input[type="submit"]',array('login' => array(
        'username' => $user,
        'password' => $pass
        )))->
      with('user')->begin()->
        isAuthenticated(true)->
      end()
    ;
    return $this;
  }
  
  public function addCustomUserAndLogin($name,$db_user_type,$password)
  {
  	$this->setTester('doctrine', 'sfTesterDoctrine');
  	$this->
  	  info('** add a custom user **')->
    	  get('user/new')->
    	  with('response')->begin()->
  	  click('#submit', array('users' => array('db_user_type' => $db_user_type,
  					   'family_name' => $name)))->end()->	    					   
       followRedirect()->
         info('** add login info for this user **')->
         click('#add_info')->
         with('response')->begin()->
		    isStatusCode(200)->
		    click('a.cancel_qtip #submit', array('users_login_infos' => array('user_name' => $name,
		    				'new_password' => $password,
		    				'confirm_password' => $password)))->
  		    isStatusCode(200)->end()->
       with('doctrine')->begin()->		    
       check('usersLoginInfos', array('user_name' => $name,
          						'login_type' => 'local'))->
	  end();    
	  return(Doctrine::getTable("Users")->findOneByFamilyName($name)->getId()) ;     
		   
  }
  
  public function addCustomCollection($code,$name)
  {
     
    $institution_id = $this->addCustomInstitution('Institution for test', 'ITF') ;
    $manager_id = $this->addCustomUserAndLogin('Super Collection Manager',Users::MANAGER,'nothing');    
    $encoder_id = $this->addCustomUserAndLogin('Super Encoder',Users::ENCODER,'nothing');        
    $this->
  	  info('** add a custom collection **')->
  	  get('collection/new')->
  	  with('response')->begin()->
  	  click('#submit', array('collections' => array('code' => $code,
  				  					        'name' => $name,
  					     				   'institution_ref' => $institution_id,
  					     				   'main_manager_ref' => $manager_id,
  					     				   'newVal' => array(0 => array('user_ref' => $manager_id),
  					     				                     1 => array('user_ref' => $encoder_id)
  					     				                    ))))->
  	  end()->
       with('doctrine')->begin()->		    
       	check('Collections', array('code' => $code,
	  					       'name' => $name,
		     				  'institution_ref' => $institution_id,
		     				  'main_manager_ref' => $manager_id,))->
	  end();   	  
	  return(Doctrine::getTable('Collections')->getCollectionByName($name)->getId()) ;	       	
  
  }
  
  public function addCustomInstitution($name, $add_name)
  {
  	$this->setTester('doctrine', 'sfTesterDoctrine');
  	$this->
  	  info('** add a custom institution **')->
    	  get('institution/new')->
    	  with('response')->begin()->
  	  click('#submit', array('institutions' => array('family_name' => $name,
  	  									     'additional_names' => $add_name)))->end()->	    					   
       with('doctrine')->begin()->		    
       	check('Institutions', array('family_name' => $name,
          					   'additional_names' => $add_name,
          					   'is_physical' => false))->
	  end(); 
	  return(Doctrine::getTable('Institutions')->getInstitutionByName($name)->getId()) ; 
  }
  
  public function addCustomTaxon($name, $level)
  {
  	$this->setTester('doctrine', 'sfTesterDoctrine');
  	$this->
  	  info('** add a custom Taxon **')->
    	  get('taxonomy/new')->
    	  with('response')->begin()->
  	  click('#submit', array('taxonomy' => array('name' => $name,
  	  									'level_ref' => $level)))->end()->	    					   
       with('doctrine')->begin()->		    
       	check('Taxonomy', array('name' => $name,
          				    'level_ref' => $level))->
	  end();  
	  return (Doctrine::getTable('Taxonomy')->getTaxonByName($name,$level,'/')->getId()) ;	
  }  
  
  public function addCustomExpedition($name)
  {
  	$this->setTester('doctrine', 'sfTesterDoctrine');
  	$this->
  	  info('** add a custom Expedition **')->
    	  get('expedition/new')->
    	  with('response')->begin()->
  	  click('#submit', array('expeditions' => array('name' => $name,
  	  									   'expedition_from_date' => array('day' => '12',
  	  									   							'month' => '10',
  	  									   							'year' => '1977')
  	  									   )))->end()->	    					   
       with('doctrine')->begin()->		    
       	check('Expeditions', array('name' => $name))->
	  end();  
  } 
  
  public function addCustomSpecimen($collection_code,$collection_name,$taxon_name,$taxon_level)
  {
  	$collection_id = $this->addCustomCollection($collection_code,$collection_name);
  	$taxon_id = $this->addCustomTaxon($taxon_name,$taxon_level);
  	$collector_id = $this->addCustomPeople('PeopleCollector') ;  	  	 
  	$this->
  	  info('** add a custom specimen **')->
  	  get('specimen/new')->
  	  with('response')->begin()->     
  	  click('#submit_spec_f1', array('specimen' => array(
  	  	  'collection_ref' => $collection_id,
  	  	  'taxon_ref' => $taxon_id,
  	  	  'newCode' => array(
  	  	  			0 => array('code_category' => 'secondary','code_prefix' => 'sec', 'code_prefix_separator' => '/','code' => '987', 'code_suffix' => 'ary', 								 'code_suffix_separator' => '/')
  	  	  			),
  	  	  'newCollectors' => array( 
  	  	  			0 => array('people_ref' => $collector_id)
  	  	  			),
  	  	  'newIdentification' => array(
  	  	  			0 => array('notion_date' => array('day' => 10, 'month' => 02,'year' => 1945),
  	  	  					 'notion_concerned' => 'taxonomy',
  	  	  					 'value_defined' => 'tst val ind'
  	  	  					)),
		  'acquisition_category' => 'Mission',
		  'acquisition_date' => array('day' => 01, 'month' => 06, 'year' => 1984)  	  	  			
  	       )))->end()->	
  	  with('response')->begin()->
  	  	isValid()->end()->    					   
       with('doctrine')->begin()->		    
       	check('Specimens', array('collection_ref' => $collection_id,
       						'taxon_ref' => $taxon_id,
       						'acquisition_category' => 'Mission'))->
	  end(); 
	  return ($this) ;
  }  

  public function addCustomPeople($name)
  {
  	$this->setTester('doctrine', 'sfTesterDoctrine');
  	$this->
  	  info('** add a custom people **')->
    	  get('people/new')->  	
    	  with('response')->begin()->
  	  click('#submit', array('people' => array('family_name' => $name,
  	  								 'db_people_type' => array(16))
  	  				    ))->end()->
       with('doctrine')->begin()->		    
       	check('People', array('family_name' => $name))->
	  end();   
	return (Doctrine::getTable("People")->findOneByFamilyName($name)->getId()) ;
  }
  
  public function addCustomIndividual($specimen_id)
  {
     $this->setTester('doctrine', 'sfTesterDoctrine');
     $this->
     	info('** add a custom Individual **')->
     	get('individuals/edit/spec_id/'.$specimen_id)->
     	with('response')->begin()->
     	click('#submit_spec_individual_f1', array('specimen_individuals' => array('newComments' => array(0 => array('notion_concerned'=> 'stage',
     																						  'comment' => 'stage of individual'))
     														        )))->end()->
       with('doctrine')->begin()->		    
       	check('SpecimenIndividuals', array('specimen_ref' => $specimen_id))->
	  end();   
//	return (Doctrine::getTable("SpecimenIndividuals")->findOneByFamilyName($name)->getId()) ;
     														        
     	  
  
  }
}
