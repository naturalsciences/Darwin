<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');
$browser->setTester('doctrine', 'sfTesterDoctrine');
$taxon = Doctrine::getTable('Taxonomy')->findOneByName('Falco Peregrinus Tunstall, 1771');
$taxonId = $taxon->getId();
$secondTaxon = Doctrine::getTable('Taxonomy')->findOneByName('Eucaryota');
$secondTaxonId = $secondTaxon->getId();
$collection = Doctrine::getTable('Collections')->findOneByName('Aves');
$collectionId = $collection->getId();
$collection->setCodePrefix('AVES');
$collection->setCodePrefixSeparator('/');
$collection->setCodeSuffix('LOULOU');
$collection->setCodeSuffixSeparator('-');
$collection->save();

$browser->
  info('1 - New Specimen screen')->
  get('/specimen/new')->

  with('request')->begin()->
    isParameter('module', 'specimen')->
    isParameter('action', 'new')->
  end()->

  info('1.1 - is everything ok on screen')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title','Add Specimens')->
    checkElement('.board_col',2)->
    checkElement('.board_col:first .widget',5)->
    checkElement('.board_col:last .widget',8)->
    checkElement('.board_col:first .widget:first .widget_top_bar span','Collection')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_top_bar span','Codes')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_content thead tr',2)->
    checkElement('.board_col:first .widget:nth-child(2) .widget_content thead tr:first th',7)->
    checkElement('.board_col:first .widget:nth-child(2) .widget_content thead tr:nth-child(2) th',5)->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_0 tr td',7)->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_0 tr td:first select option',3)->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_0 tr td:first select option[selected="selected"]','Main')->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_0 tr td:nth-child(2) input',1)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr', 3)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:first td input#specimen_host_taxon_ref', 1)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:first td div#specimen_host_taxon_ref_name', '-')->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:last td input#specimen_host_specimen_ref', 1)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:last td div#specimen_host_specimen_ref_name', '-')->
    checkElement('.board_col:first #refIdentifications div.widget_content table tbody tr.spec_ident_data', 1)->
    checkElement('.board_col:first #refIdentifications div.widget_content table tbody tr.spec_ident_identifiers', 1)->
    checkElement('.board_col:first #refIdentifications div.widget_content table tbody tr:first td:nth-child(3) select#specimen_newIdentification_0_notion_concerned option', 5)->
    checkElement('.board_col:last .widget:first .widget_top_bar span','Acquisition')->
    checkElement('.board_col:last .widget:nth-child(2) .widget_top_bar span','Expedition')->
    checkElement('.board_col:last .widget:nth-child(3) .widget_top_bar span','I.G. number')->
    checkElement('.board_col:last .widget:nth-child(4) .widget_top_bar span','Sampling location')->
    checkElement('.board_col:last .widget:nth-child(5) .widget_top_bar span','Collectors')->    
    checkElement('.board_col:last .widget:nth-child(6) .widget_top_bar span','Properties')->    
    checkElement('.board_col:last .widget:nth-child(7) .widget_top_bar span','Comments')->    
  end();

$browser->
  info('2 - Specimen search')->
  get('/specimen/index')->

  with('request')->begin()->
    isParameter('module', 'specimen')->
    isParameter('action', 'index')->
  end()->

  info('2.1 - is everything ok on screen')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table#search tbody tr td:first input#searchSpecimen_taxon_name',1)->
    checkElement('table#search tbody tr td:first input#searchSpecimen_caller_id',1)->
    checkElement('table#search tbody tr td:last select#searchSpecimen_taxon_level',1)->
    checkElement('table#search tbody tr td:last select#searchSpecimen_taxon_level option',55)->
  end()->
  info('2.2 - Post waiting for the full results table and the pager')->
  post('/specimen/search', array('is_choose'=>0, 'searchSpecimen'=>array('taxon_name'=>'', 'taxon_level'=>'')))->
  with('response')->
  begin()->
    isStatusCode()->
    checkElement('ul.pager_nav li', 10)->
    checkElement('ul.pager_nav li.page_selected', '[1]')->
    checkElement('.pager li a span.nav_arrow', 0)->
    checkElement('div.paging_info table td:nth-child(2)', 1)->
    checkElement('div.paging_info table td:last-child select[id="searchSpecimen_rec_per_page"]', 1)->
    checkElement('table.results tbody tr', 4)->
    checkElement('table.results tbody tr td:nth-child(2)', 'Animalia')->
    checkElement('table.results thead th:nth-child(2) a.sort span.order_sign_down')->
  end()->  
  info('2.3 - Click to sort on name descending...')->
  post('/specimen/search', array('orderby'=>'t.name', 'orderdir'=>'desc', 'page'=>1, 'is_choose'=>0, 'searchSpecimen'=>array('taxon_name'=>'', 'taxon_level'=>'')))->
  with('response')->
  begin()->
    checkElement('table.results thead th:nth-child(2) a.sort span.order_sign_up')->
    checkElement('table.results tbody tr td:nth-child(2)', 'Falco Peregrinus Tunstall, 1771')->
  end()->
  info('2.4 - Select only species level...')->
  post('/specimen/search', array('orderby'=>'t.name', 'orderdir'=>'asc', 'page'=>1, 'is_choose'=>0, 'searchSpecimen'=>array('taxon_name'=>'', 'taxon_level'=>'48')))->
  with('response')->
  begin()->
    checkElement('table.results tbody tr', 1)->
    checkElement('table.results tbody tr td:nth-child(2)', 'Falco Peregrinus')->
  end()->
  info('2.5 - Click on edition...')->
  click('.edit a')->
  with('response')->
  begin()->
    isStatusCode(200)->
    checkElement('title','Edit Specimen')->
    checkElement('.board_col',2)->
    checkElement('.board_col:first .widget',5)->
    checkElement('.board_col:last .widget',8)->
    checkElement('.board_col:first .widget:first .widget_top_bar span','Collection')->
    checkElement('.board_col:first .widget:first .widget_content div#specimen_collection_ref_name','Vertebrates')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_top_bar span','Codes')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_content div#specimen_taxon_ref_name','Falco Peregrinus')->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_0 tr td:first select option[selected="selected"]','Main')->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_1 tr:last td:first select option[selected="selected"]','Second.')->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_0 tr:first td:nth-child(2) input#specimen_Codes_0_code_prefix',1)->
    checkElement('.board_col:first #refCodes div.widget_content tbody#code_1 tr:last td:nth-child(2) input#specimen_Codes_1_code_prefix',1)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr', 3)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:first td input#specimen_host_taxon_ref', 1)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:first td div#specimen_host_taxon_ref_name', '-')->
    checkElement('.board_col:first #refIdentifications div.widget_content table#identifications', 1)->
    checkElement('.board_col:first #refIdentifications div.widget_content table#identifications tr', 2)->
    checkElement('.board_col:last .widget:first .widget_top_bar span','Acquisition')->
    checkElement('.board_col:last .widget:nth-child(2) .widget_top_bar span','Expedition')->
    checkElement('.board_col:last .widget:nth-child(3) .widget_top_bar span','I.G. number')->
    checkElement('.board_col:last .widget:nth-child(4) .widget_top_bar span','Sampling location')->
    checkElement('.board_col:last .widget:nth-child(5) .widget_top_bar span','Collectors')->    
    checkElement('.board_col:last .widget:nth-child(6) .widget_top_bar span','Properties')->    
    checkElement('.board_col:last .widget:nth-child(7) .widget_top_bar span','Comments')->        
  end()->
  info('3 - Edit specimen - Change Taxon')->
  click('Save', 
        array('specimen' => array('taxon_ref'  => $taxonId,
                                  'collection_ref' => $collectionId,
                                  'host_taxon_ref' => $secondTaxonId
                                 )
             )
       )->
  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.board_col:first .widget:first .widget_content div#specimen_collection_ref_name','Aves')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_content div#specimen_taxon_ref_name','Falco Peregrinus Tunstall, 1771')->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr', 3)->
    checkElement('.board_col:first #refHosts div.widget_content table tbody tr:first td div#specimen_host_taxon_ref_name', 'Eucaryota')->
  end()->
  
  info('4 - Check sameTaxon action call')->
  info('4.1 - ...without arguments')->  
  get('/specimen/sameTaxon')->
  with('response')->begin()->
    matches('/ok/')->
  end();

$specimens = Doctrine::getTable('Specimens')->findAll();
$specId = $specimens[0]->getId();
$browser->
  info('4.2 - ...with a specimen id and a taxon id different from one associated to specimen called')->  
  get('specimen/sameTaxon', array('specId'=>$specId, 'taxonId'=>'-1'))->
  with('response')->begin()->
    matches('/not ok/')->
  end()->
  info('4.3 - ...with a specimen id and a corresponding taxon id')->  
  get('specimen/sameTaxon', array('specId'=>$specId, 'taxonId'=>$specimens[0]->Taxonomy->getId()))->
  with('response')->begin()->
    matches('/ok/')->
  end()->
  info('5 - Check getTaxon action call')->
  info('5.1 - ...without arguments')->  
  get('specimen/getTaxon')->
  with('response')->begin()->
    isStatusCode(404)->
  end()->
  info('5.2 - ...with a wrong specimen id')->
  get('specimen/getTaxon', array('specId'=>'0', 'targetField'=>'specimen_host_taxon'))->
  with('response')->begin()->
    isStatusCode(404)->
  end()->
  info('5.3 - ...with correct infos')->
  get('specimen/getTaxon', array('specId'=>$specId, 'targetField'=>'specimen_host_taxon'))->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/","specimen_host_taxon_name":"Animalia"}/')->
  end();

$num = 5;

$browser->
  info('5 - Check AddCode method call')->
  get('specimen/addCode', array('id'=>$specId, 'num'=>$num, 'collectionId'=>$collectionId))->
  with('response')->begin()->
    isStatusCode()->
    checkElement('tr td:first select#specimen_newCode_'.$num.'_code_category',1)->
    checkElement('tr td:nth-child(2) input#specimen_newCode_'.$num.'_code_prefix',1)->
    checkElement('tr td:nth-child(3) div#specimen_newCode_'.$num.'_code_prefix_separator_parent',1)->
    checkElement('tr td:nth-child(4) input#specimen_newCode_'.$num.'_code',1)->
    checkElement('tr td:nth-child(5) div#specimen_newCode_'.$num.'_code_suffix_separator_parent',1)->
    checkElement('tr td:nth-child(6) input#specimen_newCode_'.$num.'_code_suffix',1)->
  end();

$num = 1;
$identifier_num = 1;

$browser->
  info('6 - Check AddIdentification method call')->
  get('specimen/addIdentification', array('spec_id'=>$specId, 'num'=>$num))->
  with('response')->begin()->
    isStatusCode()->
    checkElement('tr:first td:first[class="spec_ident_handle"]',1)->
    checkElement('tr:first td:nth-child(3) select#specimen_newIdentification_'.$num.'_notion_concerned option',5)->
    checkElement('tr:first td:nth-child(3) select#specimen_newIdentification_'.$num.'_notion_concerned option:first','Taxon.')->
    checkElement('tr:first td:last input:last[id="specimen_newIdentification_'.$num.'_order_by"][value="0"]',1)->
    checkElement('tr:nth-child(2)[class="spec_ident_identifiers"]',1)->
    checkElement('tr:nth-child(2)[class="spec_ident_identifiers"] td:nth-child(2) table#spec_ident_identifiers_'.$num,1)->
    checkElement('tr:nth-child(2)[class="spec_ident_identifiers"] td:nth-child(2) table#spec_ident_identifiers_'.$num.' tfoot div a[href="/index.php/specimen/addIdentifier/spec_id/'.$specId.'/num/'.$num.'/identifier_num/"]',1)->
  end();

$browser->
  info('7 - Check AddIdentifier method call')->
  get('specimen/addIdentifier', array('spec_id'=>$specId, 'num'=>$num, 'identifier_num'=>$identifier_num))->
  with('response')->begin()->
    isStatusCode()->
    checkElement('tbody.spec_ident_identifiers_data tr:first[class="spec_ident_identifiers_data"] td:first[class="spec_ident_identifiers_handle"]',1)->
    checkElement('tbody.spec_ident_identifiers_data tr:first[class="spec_ident_identifiers_data"] td:nth-child(2) input:first[id="specimen_newIdentification_'.$num.'_newIdentifier_'.$identifier_num.'_people_ref"]',1)->
    checkElement('tbody.spec_ident_identifiers_data tr:first[class="spec_ident_identifiers_data"] td:nth-child(2) div:first[id="specimen_newIdentification_'.$num.'_newIdentifier_'.$identifier_num.'_people_ref_name"]','-')->
    checkElement('tbody.spec_ident_identifiers_data tr:first[class="spec_ident_identifiers_data"] td:nth-child(2) div:last a[href="/index.php/people/choose/only_role/4"]','Choose !')->
  end();

$browser->
  info('8 - add a property')->
  get('property/add/table/specimens/id/'.$specId)->
  with('response')->begin()->
    isStatusCode(200)->
    click('#submit',
          array('catalogue_properties' => array('property_type' => 'physical measurement',
          							   'property_sub_type' => 'wideness',
          							   'newVal' => array(0 => array('property_value' => 120,
  	                                   				       'property_accuracy' => 10)
                                                      			 ,
                                                      			 1 => array('property_value' => 70,
  	                                   				       'property_accuracy' => 7)
                                                      			 )
                                                      )
             )
       )->end()->       
with('doctrine')->begin()
   ->check('catalogueProperties', array('property_type' => 'physical measurement',
          							   'property_sub_type' => 'wideness'))
   ->end(); 
          							  
$browser->addCustomSpecimen();
$browser->with('response')->begin()->
      isRedirected()->
      followredirect()->
  end()->
  with('response')->begin()->
  isStatusCode()->
  checkElement('.board_col:first .widget:nth-child(2) tbody',2)->
  checkElement('.board_col:first .widget:nth-child(5) tr.spec_ident_identifiers_data',2)->
  checkElement('table.collectors tr.spec_ident_collectors_data',2)->
  checkElement('#specimen_Comments_0_comment','Test comment for a collector')-> 
  checkElement('.board_col:first .widget:nth-child(8) li#specimensAccompanying',1)->
  click('#spec_delete')->
  end() ;

$browser->  
  info('9 - check if specimen is well deleted')->
  get('specimens/edit/id/'.$specId)->
  with('response')->begin()->
    isStatusCode(404)->
    end();

