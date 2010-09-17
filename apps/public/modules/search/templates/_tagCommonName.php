              <td class="col_taxon_common_name">
                <?php if($spec->checkIfCommonName($spec->getTaxonRef(),$common_names['taxonomy'])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['taxonomy'][$spec->getTaxonRef()]['name']) as $tag) : ?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>
                <?php else : ?>                
                  <?php echo ('-') ; ?>
                <?php endif ; ?>
              </td>
              <td class="col_chrono_common_name">
                <?php if($spec->checkIfCommonName($spec->getChronoRef(),$common_names['chronostratigraphy'])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['chronostratigraphy'][$spec->getChronoRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>                
                <?php else : ?>   
                  <?php echo ('-') ; ?>
                <?php endif ; ?>
              </td>
              <td class="col_litho_common_name"> 
                <?php if($spec->checkIfCommonName($spec->getLithoRef(),$common_names['lithostratigraphy'])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['lithostratigraphy'][$spec->getLithoRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>  
                <?php else : ?>  
                  <?php echo ('-') ; ?>
                <?php endif ; ?>
              </td> 
              <td class="col_lithology_common_name">
                <?php if($spec->checkIfCommonName($spec->getLithologyRef(),$common_names['lithology'])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['lithology'][$spec->getLithologyRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>                  
                <?php else : ?>   
                  <?php echo ('-') ; ?>                
                <?php endif ; ?>
              </td>  
              <td class="col_mineral_common_name"> 
                <?php if($spec->checkIfCommonName($spec->getMineralRef(),$common_names['mineralogy'])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['mineralogy'][$spec->getMineralRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>                  
                <?php else : ?>  
                  <?php echo ('-') ; ?>
                <?php endif ; ?>
              </td>
