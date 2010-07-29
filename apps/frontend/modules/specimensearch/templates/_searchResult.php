<div class="page">
  <?php if(isset($specimensearch) && $specimensearch->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>   
    <?php echo $specimensearch->count() ;  ?>
    <?php
      if($orderDir=='asc')
        $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
      else
        $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
    ?>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>
    <div class="results_container">
      <select id="all_fields">
        <option alt="display" value="category" style="background-image:url(images/checkbox_checked.png)">Category</option>
        <option alt="display" value="collection">Collection</option>
        <option alt="display" value="taxon">Taxon</option>
        <option alt="display" value="type">Type ?</option>       
        <option alt="display" value="gtu">Sampling location</option>
        <option alt="hidden" value="chrono">Chronostratigraphic unit</option>
        <option alt="hidden" value="litho">Lithostratigraphic unit</option>
        <option alt="hidden" value="lithologic">Lithologic unit</option>
        <option alt="hidden" value="mineral">Mineralogic unit</option>
        <option alt="hidden" value="expedition">Expedition</option>
        <option alt="hidden" value="count">Counts</option>
      </select>
      <table class="spec_results">
        <thead>
          <tr>
            <th></th>
            <th>
               <?php echo image_tag('thumbtack.png', array('alt' =>  __('Save this result'))) ; ?>
            </th>
            <th id="col_category">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=category'.( ($orderBy=='category' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Category');?>
                <?php if($orderBy=='category') echo $orderSign ?>
              </a>
            </th>
            <th id="col_collection">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=collection_name'.( ($orderBy=='collection_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('Collection');?>
                <?php if($orderBy=='collection_name') echo $orderSign ?>
              </a>            
            </th>            
            <th id="col_taxon">
               <a class="sort" href="<?php echo url_for($s_url.'&orderby=taxon_name'.( ($orderBy=='taxon_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Taxon');?>
                <?php if($orderBy=='taxon_name') echo $orderSign ?>
              </a>           
            </th>            
            <th id="col_type">
              <?php echo __('Type ?');?>
            </th> 
            <th id="col_gtu">                          
              <?php echo __('Sampling locations');?>
            </th>            
            <th id="col_chrono">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=chrono_name'.( ($orderBy=='chrono_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Chronostratigraphic unit');?>
                <?php if($orderBy=='chrono_name') echo $orderSign ?>
              </a>            
            </th>            
            <th id="col_litho">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=litho_name'.( ($orderBy=='litho_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Lithostratigraphic unit');?>
                <?php if($orderBy=='litho_name') echo $orderSign ?>
              </a>            
            </th>
            <th id="col_lithologic">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=lithology_name'.( ($orderBy=='lithology_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('lithologic unit');?>
                <?php if($orderBy=='lithology_name') echo $orderSign ?>
              </a>            
            </th>
            <th id="col_mineral">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=mineral_name'.( ($orderBy=='mineral_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Mineralogic unit');?>
                <?php if($orderBy=='mineral_name') echo $orderSign ?>
              </a>            
            </th>
            <th id="col_expedition">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=expedition_name'.( ($orderBy=='expedition_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('Expedition');?>
                <?php if($orderBy=='expedition_name') echo $orderSign ?>
              </a>            
            </th>
            <th id="col_count">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=specimen_count_max'.( ($orderBy=='specimen_count_max' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('Count');?>
                <?php if($orderBy=='specimen_count_max') echo $orderSign ?>
              </a>            
            </th>                        
            <th>&nbsp;</th>
          </tr>
        </thead>
        <?php foreach($specimensearch as $specimen):?>
          <tbody>
            <tr class="rid_<?php echo $specimen->getId(); ?>">
              <td rowspan="2">
                <?php echo image_tag('blue_expand.png', array('alt' => '+', 'class'=> 'tree_cmd_td collapsed')); ?>
                <?php echo image_tag('blue_expand_up.png', array('alt' => '-', 'class'=> 'tree_cmd_td expanded')); ?>
              </td>
              <td>
                <?php echo image_tag('thumbtack.png', array('alt' =>  __('Save this result'))) ; ?>
              </td>              
              <td id="col_category">
                <?php echo $specimen->getCategory() == 'physical'? image_tag('physical.png', array('alt' => __('physical'), 'class'=> 'tree_cmd collapsed')):
                                                             image_tag('non_physical.gif', array('alt' => __('other'), 'class'=> 'tree_cmd collapsed')) ;?>
              </td>
              <td  id="col_collection">
                <?php if($specimen->getCollectionRef() > 0) : ?>
                  <a href="<?php echo url_for('collection/edit?id='.$specimen->getCollectionRef());?>"><?php echo $specimen->getCollectionName();?></a>
                <?php endif ; ?>&nbsp;
              </td>
              <td id="col_taxon">
                <?php if($specimen->getTaxonRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=taxon_".$specimen->getId()."_info");?>
                  <a href="<?php echo url_for('taxonomy/edit?id='.$specimen->getTaxonRef());?>"><?php echo $specimen->getTaxonName();?></a>
                  <div id="taxon_<?php echo $specimen->getId();?>_tree" class="tree">
                  </div>
                  <script type="text/javascript">
                     $('#taxon_<?php echo $specimen->getId();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#taxon_<?php echo $specimen->getId();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=taxonomy&id=".$specimen->getTaxonRef()) ;?>',function (html){
                           item_row.find('#taxon_<?php echo $specimen->getId();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#taxon_<?php echo $specimen->getId();?>_tree').slideUp();
                     });
                  </script>                  
                <?php endif ; ?>&nbsp;
              </td>
              <td id="col_type" class="with_type">
                <?php if($specimen->getWithTypes()) : ?>
                  <?php echo image_tag('star_full.png', array('class'=> 'tree_cmd with_type')) ; ?>
                <?php else : ?>
                  <?php echo image_tag('star_empty.png', array('class'=> 'tree_cmd with_type')) ; ?>
                <?php endif ; ?>&nbsp;
              </td>        
              <td id="col_gtu">
                <?php if($specimen->getGtuRef() > 0) : ?>
                  <?php image_tag('info.png',"title=info class=info id=gtu_".$specimen->getId()."_info");?>                 
                  <div id="gtu_<?php echo $specimen->getId();?>_tree" class="tree">
                  </div>
                  <script type="text/javascript">
                     $('#gtu_<?php echo $specimen->getId();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#gtu_<?php echo $specimen->getId();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=gtu&id=".$specimen->getGtuRef()) ;?>',function (html){
                           item_row.find('#gtu_<?php echo $specimen->getId();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#gtu_<?php echo $specimen->getId();?>_tree').slideUp();
                     });
                  </script>
                  <a href="gtu/edit/id/<?php echo $specimen->getGtuRef();?>"><?php echo $specimen->getGtuName();?></a>
                <?php endif ; ?>&nbsp;                
              </td>                      
              <td  id="col_chrono">
                <?php if($specimen->getChronoRef() > 0) : ?>              
                  <a href="chronostratigraphy/edit/id/<?php echo $specimen->getChronoRef();?>"><?php echo $specimen->getChronoName();?></a>
                <?php endif ; ?>&nbsp;                
              </td>
              <td  id="col_litho">
                <?php if($specimen->getLithoRef() > 0) : ?>              
                  <a href="lithostratigraphy/edit/id/<?php echo $specimen->getLithoRef();?>"><?php echo $specimen->getLithoName();?></a>
                <?php endif ; ?>&nbsp;                
              </td> 
              <td id="col_lithologic">
                <?php if($specimen->getLithologyRef() > 0) : ?>              
                  <a href="lithology/edit/id/<?php echo $specimen->getLithologyRef();?>"><?php echo $specimen->getLithologyName();?></a>
                <?php endif ; ?>&nbsp;                
              </td>
              <td id="col_mineral">
                <?php if($specimen->getMineralRef() > 0) : ?>              
                  <a href="Mineralogy/edit/id/<?php echo $specimen->getMineralRef();?>"><?php echo $specimen->getMineralName();?></a>
                <?php endif ; ?>&nbsp;                
              </td>              
              <td id="col_expedition">
                <?php if($specimen->getExpeditionRef() > 0) : ?>              
                  <a href="expedition/edit/id/<?php echo $specimen->getExpeditionRef();?>"><?php echo $specimen->getExpeditionName();?></a>
                <?php endif ; ?>&nbsp;                
              </td> 
              <td id="col_count">            
                  <?php echo $specimen->getSpecimenCountMax();?>                
              </td>                                                                    
              <td rowspan="2">
                  <?php echo link_to(image_tag('edit.png'),'specimen/edit?id='.$specimen->getSpecRef());?>
              </td>
            </tr>
            <tr id="tr_individual_<?php echo $specimen->getId();?>">
              <td colspan='12'>
                <div id="container_individual_<?php echo $specimen->getId();?>" class="tree">
                </div>
                 <script type="text/javascript">
                 $('tr.rid_<?php echo $specimen->getId(); ?> img.collapsed').click(function() 
                 {
                    $(this).hide();
                    $(this).siblings('.expanded').show();
                    $.get('<?php echo url_for("specimensearch/individualTree?id=".$specimen->getSpecRef()) ;?>',function (html){
                          // $('#tr_individual_<?php echo $specimen->getId();?>').removeClass('hidden') ;
                           $('#container_individual_<?php echo $specimen->getId();?>').html(html).slideDown();
                           });
                 });  
                 $('tr.rid_<?php echo $specimen->getId(); ?> img.expanded').click(function() 
                 {
                    $(this).hide();
                    $(this).siblings('.collapsed').show();
                    $('#container_individual_<?php echo $specimen->getId();?>').slideUp();
                    //$('#tr_individual_<?php echo $specimen->getId();?>').addClass('hidden') ;
                 });              
                </script>
              </td>
            <tr>
          </tbody>
          <?php endforeach;?>
      </table>
    </div>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php else:?>
    <?php echo __('No Specimen Matching');?>
  <?php endif;?>
</div>  
