              <td class="col_taxon_common_name">
                <?php if(isset($common_names['taxonomy'][$spec->getTaxonRef()])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['taxonomy'][$spec->getTaxonRef()]['name']) as $tag) : ?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>
                <?php else : ?>-
                <?php endif ; ?>
              </td>
              <td class="col_chrono_common_name">
                <?php if(isset($common_names['chronostratigraphy'][$spec->getTaxonRef()])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['chronostratigraphy'][$spec->getChronoRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>                
                <?php else : ?>-
                <?php endif ; ?>
              </td>
              <td class="col_litho_common_name"> 
                <?php if(isset($common_names['lithostratigraphy'][$spec->getTaxonRef()])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['lithostratigraphy'][$spec->getLithoRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>:q
                    <?php endforeach;?>
                  </ul>  
                <?php else : ?>-
                <?php endif ; ?>
              </td> 
              <td class="col_lithologic_common_name">
                <?php if(isset($common_names['lithology'][$spec->getTaxonRef()])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['lithology'][$spec->getLithologyRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>                  
                <?php else : ?>-          
                <?php endif ; ?>
              </td>  
              <td class="col_mineral_common_name"> 
                <?php if(isset($common_names['mineralogy'][$spec->getTaxonRef()])) : ?>
                  <ul class="country_tags">
                    <?php foreach(explode(',',$common_names['mineralogy'][$spec->getMineralRef()]['name']) as $tag):?>
                      <li><?php echo $tag;?></li>
                    <?php endforeach;?>
                  </ul>                  
                <?php else : ?>-
                <?php endif ; ?>
              </td>
