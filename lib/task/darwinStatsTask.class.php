<?php

class darwinStatsTask extends sfBaseTask
{
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
