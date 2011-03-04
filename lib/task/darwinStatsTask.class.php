<?php

class darwinStatsTask extends sfBaseTask
{
  private $request_array = array(
  "SELECT DISTINCT individual_type, count(individual_type) as Count FROM darwin_flat Group by individual_type Order by individual_type;",
  
  
  ) ;
  protected function configure()
  {
    $this->namespace        = 'darwin';
    $this->name             = 'gen-stats';
    $this->briefDescription = 'Generate statistics';
    $this->detailedDescription = <<<EOF
The [darwin:gen-stats|INFO] task launch all request stored to create
a .yml file with all request results
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
 
  }
}
