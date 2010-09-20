<div>
  <?php if(isset($search) && $search->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage)):?>   
    <?php
      $i = 0 ;
      if($orderDir=='asc')
        $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
      else
        $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
    ?>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>
      <ul class="sf-menu column_menu">
        <li class="head"><?php echo __('Choose columns to display') ; ?><br/><?php echo image_tag('column_display_expand.png', array('id'=>'column_display_expand','alt' => __('expand'))) ; ?>
          <ul>
            <?php
            $cols = array(
              'collection' => __("Collection"),
              'taxon' => __("Taxonomy"),
              'gtu' => __("Countries"),
              'chrono' => __("Chronostratigraphic unit"),
              'litho' => __("Lithostratigraphic unit"),
              'lithology' => __("lithology unit"),
              'mineral' => __("Mineralogic"),
              'taxon_common_name' => __("Taxon Common Names"),
              'chrono_common_name' => __("Chrono Common Names"),
              'litho_common_name' => __("Litho Common Names"),
              'lithology_common_name' => __("lithology Common Names"),
              'mineral_common_name' => __("Mineral Common Names"),              
              'type' => __("Type"),
              'sex' => __("Sex"),
              'stage' => __("Stage"),              
              );
            foreach($cols as $c_name => $c_title):?>
              <li class="<?php echo $field_to_show[$c_name]; ?>" id="li_<?php echo $c_name;?>">
                <span class="<?php echo($field_to_show[$c_name]=='uncheck'?'hidden':''); ?>">&#10003</span>
                <span class="<?php echo($field_to_show[$c_name]=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?php echo $c_title;?>
              </li>
            <?php endforeach;?>
          </ul>
        </li>
      </ul>
      <table class="spec_results">
        <thead>
          <tr>
            <th></th>
            <th class="col_collection">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=collection_name'.( ($orderBy=='collection_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('Collection');?>
                <?php if($orderBy=='collection_name') echo $orderSign ?>
              </a>            
            </th>            
            <th class="col_taxon">
               <a class="sort" href="<?php echo url_for($s_url.'&orderby=taxon_name'.( ($orderBy=='taxon_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Taxon');?>
                <?php if($orderBy=='taxon_name') echo $orderSign ?>
              </a>           
            </th>                                  
            <th class="col_chrono">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=chrono_name'.( ($orderBy=='chrono_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Chronostratigraphic unit');?>
                <?php if($orderBy=='chrono_name') echo $orderSign ?>
              </a>            
            </th>            
            <th class="col_litho">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=litho_name'.( ($orderBy=='litho_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Lithostratigraphic unit');?>
                <?php if($orderBy=='litho_name') echo $orderSign ?>
              </a>            
            </th>
            <th class="col_lithology">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=lithology_name'.( ($orderBy=='lithology_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('lithology unit');?>
                <?php if($orderBy=='lithology_name') echo $orderSign ?>
              </a>            
            </th>
            <th class="col_mineral">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=mineral_name'.( ($orderBy=='mineral_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Mineralogic unit');?>
                <?php if($orderBy=='mineral_name') echo $orderSign ?>
              </a>            
            </th>
            <th class="col_taxon_common_name">
                <?php echo __('Taxonomy Common Name');?>    
            </th>                                  
            <th class="col_chrono_common_name">
                <?php echo __('Chronostratigraphic Common Name');?>      
            </th>            
            <th class="col_litho_common_name">
                <?php echo __('Lithostratigraphic Common Name');?>      
            </th>
            <th class="col_lithology_common_name">
                <?php echo __('lithology Common Name');?>       
            </th>
            <th class="col_mineral_common_name">
                <?php echo __('Mineralogic Common Name');?>         
            </th>            
            <th class="col_gtu">                          
              <?php echo __('Countries');?>
            </th>            
            <th class="col_type">
                <a class="sort" href="<?php echo url_for($s_url.'&orderby=with_types'.( ($orderBy=='individual_type' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
              <?php echo __('Type');?>
              <?php if($orderBy=='individual_type') echo $orderSign ?>              
            </th>
            <th class="col_sex">
                <a class="sort" href="<?php echo url_for($s_url.'&orderby=individual_sex'.( ($orderBy=='individual_sex' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
              <?php echo __('Sex');?>
              <?php if($orderBy=='individual_sex') echo $orderSign ?>              
            </th>        
            <th class="col_stage">
                <a class="sort" href="<?php echo url_for($s_url.'&orderby=individual_stage'.( ($orderBy=='individual_stage' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
              <?php echo __('Stage');?>
              <?php if($orderBy=='individual_stage') echo $orderSign ?>              
            </th>                   
          </tr>
        </thead>
        <?php foreach($search as $specimen):?>
          <tbody>
            <tr class="rid_<?php echo $i++ ; ?>">
              <td>
                  <?php echo link_to(image_tag('edit.png', array("title" => __("View"))),'search/view?id='.$specimen->getSpecRef());?>
              </td>
              <td  class="col_collection">
                <?php if($specimen->getCollectionRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=collection_".$i."_info");?>
                  <?php echo $specimen->getCollectionName();?>
                  <div id="collection_<?php echo $i;?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#collection_<?php echo $i;?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#collection_<?php echo $i;?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("search/tree?table=collections&id=".$specimen->getCollectionRef()) ;?>',function (html){
                           item_row.find('#collection_<?php echo $i;?>_tree').html(html).slideDown();
                           });
                       }
                       $('#collection_<?php echo $i;?>_tree').slideUp();
                     });
                  </script>                  
                <?php endif ; ?>&nbsp;
              </td>
              <td class="col_taxon">
                <?php if($specimen->getTaxonRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=taxon_".$i."_info");?>
                  <?php echo $specimen->getTaxonName();?>
                  <div id="taxon_<?php echo $i;?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#taxon_<?php echo $i;?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#taxon_<?php echo $i;?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("search/tree?table=taxonomy&id=".$specimen->getTaxonRef()) ;?>',function (html){
                           item_row.find('#taxon_<?php echo $i;?>_tree').html(html).slideDown();
                           });
                       }
                       $('#taxon_<?php echo $i;?>_tree').slideUp();
                     });
                  </script>                  
                <?php endif ; ?>&nbsp;
              </td>              
              <td  class="col_chrono">
                <?php if($specimen->getChronoRef() > 0) : ?>              
                  <?php echo image_tag('info.png',"title=info class=info id=chrono_".$i."_info");?>
                  <?php echo $specimen->getChronoName();?>
                  <div id="chrono_<?php echo $i;?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#chrono_<?php echo $i;?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#chrono_<?php echo $i;?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("search/tree?table=chronostratigraphy&id=".$specimen->getChronoRef()) ;?>',function (html){
                           item_row.find('#chrono_<?php echo $i;?>_tree').html(html).slideDown();
                           });
                       }
                       $('#chrono_<?php echo $i;?>_tree').slideUp();
                     });
                  </script>  
                <?php endif ; ?>&nbsp;                              
              </td>
              <td  class="col_litho">
                <?php if($specimen->getLithoRef() > 0) : ?>              
                  <?php echo image_tag('info.png',"title=info class=info id=litho_".$i."_info");?>                
                  <?php echo $specimen->getLithoName();?>
                  <div id="litho_<?php echo $i;?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#litho_<?php echo $i;?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#litho_<?php echo $i;?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("search/tree?table=lithostratigraphy&id=".$specimen->getLithoRef()) ;?>',function (html){
                           item_row.find('#litho_<?php echo $i;?>_tree').html(html).slideDown();
                           });
                       }
                       $('#litho_<?php echo $i;?>_tree').slideUp();
                     });
                  </script> 
                <?php endif ; ?>&nbsp;                
              </td> 
              <td class="col_lithology">
                <?php if($specimen->getLithologyRef() > 0) : ?>              
                  <?php echo image_tag('info.png',"title=info class=info id=lithology_".$i."_info");?>                                
                  <?php echo $specimen->getLithologyName();?>
                  <div id="lithology_<?php echo $i;?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#lithology_<?php echo $i;?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#lithology_<?php echo $i;?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("search/tree?table=lithology&id=".$specimen->getLithologyRef()) ;?>',function (html){
                           item_row.find('#lithology_<?php echo $i;?>_tree').html(html).slideDown();
                           });
                       }
                       $('#lithology_<?php echo $i;?>_tree').slideUp();
                     });
                  </script> 
                <?php endif ; ?>&nbsp;
              </td>
              <td class="col_mineral">
                <?php if($specimen->getMineralRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=mineral_".$i."_info");?>                
                  <?php echo $specimen->getMineralName();?>
                  <div id="mineral_<?php echo $i;?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#mineral_<?php echo $i;?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#mineral_<?php echo $i;?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("search/tree?table=mineralogy&id=".$specimen->getMineralRef()) ;?>',function (html){
                           item_row.find('#mineral_<?php echo $i;?>_tree').html(html).slideDown();
                           });
                       }
                       $('#mineral_<?php echo $i;?>_tree').slideUp();
                     });
                  </script> 
                <?php endif ; ?>&nbsp;
              </td> 
              <?php include_partial('tagCommonName',array('common_names'=> $common_names, 'spec'=> $specimen)) ; ?>
              <td class="col_gtu">
                <?php if($specimen->getGtuRef() > 0) : ?>
                  <?php if($specimen->getGtuCountryTagValue() != "") : ?>                  
                    <?php echo image_tag('info.png',"title=info class=info id=gtu_ctr_".$i."_info");?><?php echo $specimen->getGtuCode();?>
                    <div id="gtu_<?php echo $i;?>_details" style="display:none;"></div> 
                    <script type="text/javascript">
                    $('#gtu_ctr_<?php echo $i;?>_info').click(function() 
                    {
                      item_row = $(this).closest('tr');
                      elem = item_row.find('#gtu_<?php echo $i;?>_details');
                      if(elem.is(":hidden"))
                      { 
                        $.get('<?php echo url_for("search/completeTag?id=".$specimen->getGtuRef()) ;?>',function (html){
                          item_row.find('.general_gtu').slideUp();
                          elem.html(html).slideDown();
                        });
                        //elem.slideDown();
                      }
                      else
                      {
                        elem.slideUp();
                        item_row.find('.general_gtu').slideDown();
                      }
                    });
                  </script>
                  <?php endif ; ?>                
                <?php endif ; ?>          
              </td>                         
              <td class="col_type">
                <?php echo ($specimen->getIndividualTypeSearch()=="undefined"?"-":$specimen->getIndividualTypeSearch()) ; ?>
              </td>  
              <td class="col_sex">
                <?php echo ($specimen->getIndividualSex()=="undefined"?"-":$specimen->getIndividualSex()) ; ?>
              </td>
              <td>
                <?php echo ($specimen->getIndividualStage()=="undefined"?"-":$specimen->getIndividualStage()) ; ?>
              </td>
            </tr>            
          </tbody>
          <?php endforeach;?>
      </table>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php else:?>
    <?php echo __('No Specimen Matching');?>
  <?php endif;?>
</div>  
<script type="text/javascript">
$(document).ready(function () {
  o = {"dropShadows":false, "autoArrows":false,  "delay":400};
  $('ul.column_menu').superfish(o);

  $('ul.column_menu > li > ul > li').each(function(){
    hide_or_show($(this));
  });
  initIndividualColspan() ;
  $("ul.column_menu > li > ul > li").click(function(){
    update_list($(this));
    hide_or_show($(this));
  });  
});
</script> 
