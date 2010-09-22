<?php slot('title', __('View Specimens'));  ?>  

<div class="page viewer">
  <h2 class="title"><?php echo __("Darwin Specimen") ?></h2>
  <div class="borded  padded">
    <h2 class="title"><?php echo __("Collection") ?></h2>  
    <div class="borded">
      <table>
        <tbody>
          <tr>
            <td class="line">
                <?php echo __("Name") ; ?>: <span class="pager_nav"><?php echo $specimen->getCollectionName() ; ?></span>
                <?php echo image_tag('info.png',"title=info class=info id=collection_info");?>
              <div id="collection_tree" class="tree"></div>
                <script type="text/javascript">
                   $('#collection_info').click(function() 
                   {
                     if($('#collection_tree').is(":hidden"))
                     {
                       $.get('<?php echo url_for("search/tree?table=collections&id=".$specimen->getCollectionRef()) ;?>',function (html){
                         $('#collection_tree').html(html).slideDown();
                         });
                     }
                     $('#collection_tree').slideUp();
                   });
                </script>
              </div>
              <div class="line">
                <?php echo __("Institution") ; ?>: <span class="pager_nav"><?php echo $institute->getFamilyName() ; ?></span>
                (<span class="pager_nav"><?php echo $institute->getAdditionalNames() ; ?></span>)
              </div>            
            </td>
            <td>
              <div class="tree_view">
                <span class="line">
                <?php echo __("Corrector") ; ?>: <span class="pager_nav"><?php echo $specimen->getCollectionMainManagerFormatedName() ; ?></span>
                </span>
                <?php foreach($manager as $info) : ?>
                  <?php if($img = $info->getDisplayImage(1)) : ?>
                    <span class="line">
                      <?php echo image_tag($img,"title=info class=info");?> :
                      <span class="pager_nav"><?php echo $info->getEntry() ; ?></span>
                    </span>
                 <?php endif ; ?>
                <?php endforeach ; ?>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>      
    <?php if(count($common_names)) : ?>
    <h2 class="title"><?php echo __("Common Names") ?></h2>  
    <div class="borded">    
      <table class="classification">
        <thead>
          <tr>
            <th><?php echo __("Classification") ; ?></th>
            <th><?php echo __("Community/language") ; ?></th>
            <th><?php echo __("Names") ; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php include_partial('classification',array('common_name' => $common_names->getRawValue(), 'spec' => $specimen)) ; ?>
        </tbody>
      </table>
    </div>
    <?php endif ; ?>
    <h2 class="title"><?php echo __("Classifications") ?></h2>  
    <div class="borded">        
      <table>
        <thead>
          <tr>
            <th>&nbsp;</th>
            <th><?php echo __("level") ; ?></th>
          </tr>
        </thead>
        <tbody>
        <?php if($specimen->getTaxonRef()) : ?>
          <tr>
            <td class="line">
              <?php echo __("Taxonomy") ; ?>: <span class="pager_nav"><?php echo $specimen->getTaxonName() ; ?></span>
              <?php echo image_tag('info.png',"title=info class=info id=taxon_info");?>
            </td>
            <td class="view_level">
              <span class="pager_nav"><?php echo $specimen->getTaxonLevelRef() ; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <div id="taxon_tree" class="tree"></div>
              <script type="text/javascript">
                 $('#taxon_info').click(function() 
                 {
                   if($('#taxon_tree').is(":hidden"))
                   {
                     $.get('<?php echo url_for("search/tree?table=taxonomy&id=".$specimen->getTaxonRef()) ;?>',function (html){
                       $('#taxon_tree').html(html).slideDown();
                       });
                   }
                   $('#taxon_tree').slideUp();
                 });
              </script>
              </div>          
            </td>
            <td></td>
          </tr>
        <?php endif ; ?>
        <?php if($specimen->getChronoRef()) : ?>
          <tr>
            <td class="line">
              <?php echo __("Chronostatigraphy") ; ?>: <span class="pager_nav"><?php echo $specimen->getChronoName() ; ?></span>
              <?php echo image_tag('info.png',"title=info class=info id=chrono_info");?>
            </td>
            <td>
              <span class="pager_nav"><?php echo $specimen->getChronoLevelRef() ; ?></span>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="chrono_tree" class="tree"></div>
              <script type="text/javascript">
                 $('#chrono_info').click(function() 
                 {
                   if($('#chrono_tree').is(":hidden"))
                   {
                     $.get('<?php echo url_for("search/tree?table=chronostratigraphy&id=".$specimen->getChronoRef()) ;?>',function (html){
                       $('#chrono_tree').html(html).slideDown();
                       });
                   }
                   $('#chrono_tree').slideUp();
                 });
              </script>
              </div>          
            </td>
          </tr>
        <?php endif ; ?>     
        <?php if($specimen->getLithoRef()) : ?>
          <tr>
            <td class="line">
              <?php echo __("Lithostatigraphy") ; ?>: <span class="pager_nav"><?php echo $specimen->getLithoName() ; ?></span>
              <?php echo image_tag('info.png',"title=info class=info id=litho_info");?>
            </td>
            <td>
              <span class="pager_nav"><?php echo $specimen->getLithoLevelRef() ; ?></span>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="litho_tree" class="tree"></div>
              <script type="text/javascript">
                 $('#litho_info').click(function() 
                 {
                   if($('#litho_tree').is(":hidden"))
                   {
                     $.get('<?php echo url_for("search/tree?table=lithostratigraphy&id=".$specimen->getLithoRef()) ;?>',function (html){
                       $('#litho_tree').html(html).slideDown();
                       });
                   }
                   $('#litho_tree').slideUp();
                 });
              </script>
              </div>          
            </td>
          </tr>
        <?php endif ; ?>       
        <?php if($specimen->getLithologyRef()) : ?>
          <tr>
            <td class="line">
              <?php echo __("Lithology") ; ?>: <span class="pager_nav"><?php echo $specimen->getLithologyName() ; ?></span>
              <?php echo image_tag('info.png',"title=info class=info id=lithology_info");?>
            </td>
            <td>
              <span class="pager_nav"><?php echo $specimen->getLithologyLevelRef() ; ?></span>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="lithology_tree" class="tree"></div>
              <script type="text/javascript">
                 $('#lithology_info').click(function() 
                 {
                   if($('#lithology_tree').is(":hidden"))
                   {
                     $.get('<?php echo url_for("search/tree?table=lithology&id=".$specimen->getLithologyRef()) ;?>',function (html){
                       $('#lithology_tree').html(html).slideDown();
                       });
                   }
                   $('#lithology_tree').slideUp();
                 });
              </script>
              </div>          
            </td>
          </tr>
        <?php endif ; ?>  
        <?php if($specimen->getMineralRef()) : ?>
          <tr>
            <td class="line">
              <?php echo __("Mineralogy") ; ?>: <span class="pager_nav"><?php echo $specimen->getMineralName() ; ?></span>
              <?php echo image_tag('info.png',"title=info class=info id=mineral_info");?>
            </td>
            <td>
              <span class="pager_nav"><?php echo $specimen->getMineralLevelRef() ; ?></span>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="mineral_tree" class="tree"></div>
              <script type="text/javascript">
                 $('#mineral_info').click(function() 
                 {
                   if($('#mineral_tree').is(":hidden"))
                   {
                     $.get('<?php echo url_for("search/tree?table=mineralogy&id=".$specimen->getMineralRef()) ;?>',function (html){
                       $('#mineral_tree').html(html).slideDown();
                       });
                   }
                   $('#mineral_tree').slideUp();
                 });
              </script>
              </div>          
            </td>
          </tr>
        <?php endif ; ?>                          
        </tbody>
      </table>      
    </div>
    <h2 class="title"><?php echo __("Specimen Characteristics") ?></h2>  
    <div class="borded">        
      <table class="caract_table">
        <tr>
          <td><?php echo __("Number of individual") ; ?> :</td>
          <td><span class="pager_nav">
            <?php if($specimen->getIndividualCountMin() == $specimen->getIndividualCountMax()) 
                echo ($specimen->getIndividualCountMin()==""?"-":$specimen->getIndividualCountMin()) ;
              else
                echo __("Between ".$specimen->getIndividualCountMin()." and ".$specimen->getIndividualCountMax()) ;            
             ?></span>
          </td>
        </tr>
        <tr>
          <td><?php echo __("Type") ; ?> :</td>
          <td>
            <span class="pager_nav"><?php echo ($specimen->getIndividualTypeSearch()=="undefined"?"-":$specimen->getIndividualTypeSearch()) ; ?></span>
          </td>
        </tr>
        <tr>
          <td><?php echo __("Sex") ; ?> :</td>
          <td>
            <span class="pager_nav"><?php echo ($specimen->getIndividualSex()=="undefined"?"-":$specimen->getIndividualSex()) ; ?></span>
          </td>
        </tr>
        <tr>
          <td><?php echo __("Stage") ; ?> :</td>
          <td>
            <span class="pager_nav"><?php echo ($specimen->getIndividualStage()=="undefined"?"-":$specimen->getIndividualStage()) ; ?></span>
          </td>
        </tr>
        <tr>
          <td><?php echo __("Country(ies)") ; ?> :</td>
          <td>
            <?php if($tags) : ?> 
            <ul class="country_tags">
              <?php foreach($tags as $key=>$tag):?>
                <?php if($tag == "") echo "-" ; ?>
                <li class="tag_size_2"><?php echo $tag ;?></li>
              <?php endforeach;?>
            </ul>
            <?php else : ?>
              <span class="pager_nav">-</span>
            <?php endif ; ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
