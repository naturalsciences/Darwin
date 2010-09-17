            <?php if($spec->checkIfCommonName($spec->getTaxonRef(),$common_name['taxonomy'])) : ?>
            <?php $first=true ; ?>
            <?php foreach($common_name['taxonomy'][$spec->getTaxonRef()]['community'] as $community => $name) : ?>
              <?php if($first) : ?>
              <tr>
                <td rowspan="<?php echo count($common_name['taxonomy'][$spec->getTaxonRef()]); ?>"><?php echo __('Taxonomy') ; ?></td>
                <?php $first=false ; ?>
              <?php endif ; ?>
              <td><?php echo $community ?></td>
              <td><?php echo $name ; ?></td>
              </tr>              
              <?php endforeach ; ?>
            <?php endif ; ?>
            
            <?php if($spec->checkIfCommonName($spec->getChronoRef(),$common_name['chronostratigraphy'])) : ?>
            <?php $first=true ; ?>
            <?php foreach($common_name['chronostratigraphy'][$spec->getChronoRef()]['community'] as $community => $name) : ?>
            <?php if($first) : ?>
              <tr>
                <td rowspan="<?php echo count($common_name['chronostratigraphy'][$spec->getChronoRef()]); ?>"><?php echo __('Chronostratigraphy') ; ?></td>
                <?php $first=false ; ?>
              <?php endif ; ?>
              <td><?php echo $community ?></td>
              <td><?php echo $name ; ?></td>
              </tr>
              <?php endforeach ; ?>
            <?php endif ; ?>    
            
            <?php if($spec->checkIfCommonName($spec->getLithologyRef(),$common_name['lithology'])) : ?> 
            <?php $first=true ; ?>                       
            <?php foreach($common_name['lithology'][$spec->getLithologyRef()]['community'] as $community => $name) : ?>            
            <?php if($first) : ?>              
              <tr>
                <td rowspan="<?php echo count($common_name['lithology'][$spec->getLithologyRef()]); ?>"><?php echo __('Lithology') ; ?></td>
                <?php $first=false ; ?>
              <?php endif ; ?>
              <td><?php echo $community ?></td>
              <td><?php echo $name ; ?></td>
              </tr>
              <?php endforeach ; ?>
            <?php endif ; ?>
            
            <?php if($spec->checkIfCommonName($spec->getLithoRef(),$common_name['lithostratigraphy'])) : ?>
            <?php $first=true ; ?>            
            <?php foreach($common_name['lithostratigraphy'][$spec->getLithoRef()]['community'] as $community => $name) : ?>
            <?php if($first) : ?> 
              <tr>
                <td rowspan="<?php echo count($common_name['lithostratigraphy'][$spec->getLithoRef()]); ?>"><?php echo __('Lithostratigraphy') ; ?></td>
                <?php $first=false ; ?>
              <?php endif ; ?>
              <td><?php echo $community ?></td>
              <td><?php echo $name ; ?></td>
              </tr>
              <?php endforeach ; ?>
            <?php endif ; ?>
            
            <?php if($spec->checkIfCommonName($spec->getMineralRef(),$common_name['mineralogy'])) : ?>  
            <?php $first=true ; ?>                      
            <?php foreach($common_name['mineralogy'][$spec->getMineralRef()]['community'] as $community => $name) : ?>
            <?php if($first) : ?>
              <tr>
                <td rowspan="<?php echo count($common_name['mineralogy'][$spec->getMineralRef()]); ?>"><?php echo __('Mineralogy') ; ?></td>
                <?php $first=false ; ?>
              <?php endif ; ?>
              <td><?php echo $community ?></td>
              <td><?php echo $name ; ?></td>
              </tr>
              <?php endforeach ; ?>  
            <?php endif ; ?>          
                                     
