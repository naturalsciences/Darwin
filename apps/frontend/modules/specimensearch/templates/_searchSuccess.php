<div>
      <?php if($is_specimen_search):?>
        <input type="hidden" name="spec_search" value="<?php echo $is_specimen_search;?>" />
      <?php endif;?>
  <?php if(isset($specimensearch) && $specimensearch->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage)):?>   
    <?php
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
            <li class="<?php echo $field_to_show['category']; ?>" id="li_category">
              <span class="<?php echo($field_to_show['category']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['category']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Category");?>
            </li>
            <li class="<?php echo $field_to_show['collection']; ?>" id="li_collection">
              <span class="<?php echo($field_to_show['collection']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['collection']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Collection");?>
            </li>
            <li class="<?php echo $field_to_show['taxon']; ?>" id="li_taxon">
              <span class="<?php echo($field_to_show['taxon']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['taxon']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Taxon");?>
            </li>
            <li class="<?php echo $field_to_show['type']; ?>" id="li_type">
              <span class="<?php echo($field_to_show['type']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['type']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Type ?");?>
            </li>       
            <li class="<?php echo $field_to_show['gtu']; ?>" id="li_gtu">
              <span class="<?php echo($field_to_show['gtu']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['gtu']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Sampling location");?>
            </li>
            <li class="<?php echo $field_to_show['chrono']; ?>" id="li_chrono">
              <span class="<?php echo($field_to_show['chrono']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['chrono']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Chronostratigraphic unit");?>
            </li>
            <li class="<?php echo $field_to_show['litho']; ?>" id="li_litho">
              <span class="<?php echo($field_to_show['litho']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['litho']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Lithostratigraphic unit");?>
            </li>
            <li class="<?php echo $field_to_show['lithologic']; ?>" id="li_lithologic">
              <span class="<?php echo($field_to_show['lithologic']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['lithologic']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Lithologic unit");?>
            </li>
            <li class="<?php echo $field_to_show['mineral']; ?>" id="li_mineral">
              <span class="<?php echo($field_to_show['mineral']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['mineral']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Mineralogic unit");?>
            </li>
            <li class="<?php echo $field_to_show['expedition']; ?>" id="li_expedition">
              <span class="<?php echo($field_to_show['expedition']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['expedition']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Expedition");?>
            </li>
            <li class="<?php echo $field_to_show['count']; ?>" id="li_count">
              <span class="<?php echo($field_to_show['count']=='uncheck'?'hidden':''); ?>">&#10003</span>
              <span class="<?php echo($field_to_show['count']=='uncheck'?'':'hidden'); ?>">&#10007;</span>&nbsp;<?= __("Counts");?>
            </li>
          </ul>
        </li>
      </ul>
      <table class="spec_results">
        <thead>
          <tr>
            <th></th>
            <th>
               <?php echo image_tag('white_pin_off.png', array('alt' =>  __('Save this result'))) ; ?>
            </th>
            <th></th>
            <th class="col_category">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=category'.( ($orderBy=='category' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
                <?php echo __('Category');?>
                <?php if($orderBy=='category') echo $orderSign ?>
              </a>
            </th>
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
            <th class="col_type">
                <a class="sort" href="<?php echo url_for($s_url.'&orderby=with_types'.( ($orderBy=='with_types' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.
                 $currentPage);?>">
              <?php echo __('Type ?');?>
                <?php if($orderBy=='with_types') echo $orderSign ?>              
            </th> 
            <th class="col_gtu">                          
              <?php echo __('Sampling locations');?>
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
            <th class="col_lithologic">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=lithology_name'.( ($orderBy=='lithology_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('lithologic unit');?>
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
            <th class="col_expedition">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=expedition_name'.( ($orderBy=='expedition_name' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('Expedition');?>
                <?php if($orderBy=='expedition_name') echo $orderSign ?>
              </a>            
            </th>
            <th class="col_count">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=specimen_count_max'.( ($orderBy=='specimen_count_max' && $orderDir=='asc') ? '&orderdir=desc' :
               '').'&page='.$currentPage);?>">
                <?php echo __('Count');?>
                <?php if($orderBy=='specimen_count_max') echo $orderSign ?>
              </a>            
            </th>                        
            <th></th>
          </tr>
        </thead>
        <?php foreach($specimensearch as $specimen):?>
          <tbody>
            <tr class="rid_<?php echo $specimen->getSpecRef(); ?>">
              <td rowspan="2">
                <?php echo image_tag('blue_expand.png', array('alt' => '+', 'class'=> 'tree_cmd_td collapsed')); ?>
                <?php echo image_tag('blue_expand_up.png', array('alt' => '-', 'class'=> 'tree_cmd_td expanded')); ?>
              </td>
              <td >
                <?php if($sf_user->isPinned($specimen->getSpecRef())):?>
                  <?php echo image_tag('blue_pin_on.png', array('class'=>'pin_but pin_on','alt' =>  __('Un-Save this result'))) ; ?>
                  <?php echo image_tag('blue_pin_off.png', array('class'=>'pin_but pin_off hidden', 'alt' =>  __('Save this result'))) ; ?>
                <?php else:?>
                  <?php echo image_tag('blue_pin_on.png', array('class'=>'pin_but pin_on hidden','alt' =>  __('Un-Save this result'))) ; ?>
                  <?php echo image_tag('blue_pin_off.png', array('class'=>'pin_but pin_off', 'alt' =>  __('Save this result'))) ; ?>
                <?php endif;?>
              </td>
              <td><?php if($is_specimen_search):?><?php echo image_tag('blue_pin_del.png',array('class' => 'pin_del'));?><?php endif;?></td>
              <td class="col_category">
                <?php echo $specimen->getCategory() == 'physical'? image_tag('physical.png', array('alt' => __('physical'))):
                                                             image_tag('non_physical.gif', array('alt' => __('other'))) ;?>
              </td>
              <td  class="col_collection">
                <?php if($specimen->getCollectionRef() > 0) : ?>
                  <a href="<?php echo url_for('collection/edit?id='.$specimen->getCollectionRef());?>"><?php echo $specimen->getCollectionName();?></a>
                <?php endif ; ?>&nbsp;
              </td>
              <td class="col_taxon">
                <?php if($specimen->getTaxonRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=taxon_".$specimen->getSpecRef()."_info");?>
                  <a href="<?php echo url_for('taxonomy/edit?id='.$specimen->getTaxonRef());?>"><?php echo $specimen->getTaxonName();?></a>
                  <div id="taxon_<?php echo $specimen->getSpecRef();?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#taxon_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#taxon_<?php echo $specimen->getSpecRef();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=taxonomy&id=".$specimen->getTaxonRef()) ;?>',function (html){
                           item_row.find('#taxon_<?php echo $specimen->getSpecRef();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#taxon_<?php echo $specimen->getSpecRef();?>_tree').slideUp();
                     });
                  </script>                  
                <?php endif ; ?>&nbsp;
              </td>
              <td class="col_type">
                <?php if($specimen->getWithTypes()) : ?>
                  <?php echo image_tag('blue_favorite_on.png', array('class'=> 'tree_cmd with_type')) ; ?>
                <?php else : ?>
                  <?php echo image_tag('blue_favorite_off.png', array('class'=> 'tree_cmd with_type')) ; ?>
                <?php endif ; ?>&nbsp;
              </td>        
              <td class="col_gtu">
                <?php if($specimen->getGtuRef() > 0) : ?>
                  <script type="text/javascript">
                      $(document).ready(function () {
                        $('#gtu_ctr_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                        {
                          item_row = $(this).closest('tr');
                          elem = item_row.find('#gtu_<?php echo $specimen->getSpecRef();?>_details');
                          if(elem.is(":hidden"))
                          { 
                            $.get('<?php echo url_for("gtu/completeTag?id=".$specimen->getGtuRef()) ;?>',function (html){
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
                      });
                  </script>
                  <?php echo image_tag('info.png',"title=info class=info id=gtu_ctr_".$specimen->getSpecRef()."_info");?>
                  <div class="general_gtu">
                    <strong><?php echo __('Country');?> :</strong>
                    <?php echo $specimen->getCountryTags();?><div class="clear" ></div>
                  </div>
                 <div id="gtu_<?php echo $specimen->getSpecRef();?>_details" style="display:none;"></div>
                <?php endif ; ?>          
              </td>                      
              <td  class="col_chrono">
                <?php if($specimen->getChronoRef() > 0) : ?>              
                  <a href="chronostratigraphy/edit/id/<?php echo $specimen->getChronoRef();?>"><?php echo $specimen->getChronoName();?></a>
                <?php endif ; ?>&nbsp;                
              </td>
              <td  class="col_litho">
                <?php if($specimen->getLithoRef() > 0) : ?>              
                  <a href="lithostratigraphy/edit/id/<?php echo $specimen->getLithoRef();?>"><?php echo $specimen->getLithoName();?></a>
                <?php endif ; ?>&nbsp;                
              </td> 
              <td class="col_lithologic">
                <?php if($specimen->getLithologyRef() > 0) : ?>              
                  <a href="lithology/edit/id/<?php echo $specimen->getLithologyRef();?>"><?php echo $specimen->getLithologyName();?></a>
                <?php endif ; ?>&nbsp;
              </td>
              <td class="col_mineral">
                <?php if($specimen->getMineralRef() > 0) : ?>
                  <a href="Mineralogy/edit/id/<?php echo $specimen->getMineralRef();?>"><?php echo $specimen->getMineralName();?></a>
                <?php endif ; ?>&nbsp;
              </td>              
              <td class="col_expedition">
                <?php if($specimen->getExpeditionRef() > 0) : ?>
                  <a href="expedition/edit/id/<?php echo $specimen->getExpeditionRef();?>"><?php echo $specimen->getExpeditionName();?></a>
                <?php endif ; ?>&nbsp;
              </td> 
              <td class="col_count">
                  <?php echo $specimen->getSpecimenCountMax();?>
              </td>
              <td rowspan="2">
                  <?php echo link_to(image_tag('edit.png'),'specimen/edit?id='.$specimen->getSpecRef());?>
              </td>
            </tr>
            <tr id="tr_individual_<?php echo $specimen->getSpecRef();?>">
              <td colspan="8">
                <div id="container_individual_<?php echo $specimen->getSpecRef();?>" class="tree"></div>
                <script type="text/javascript">
                 $('tr.rid_<?php echo $specimen->getSpecRef(); ?> img.collapsed').click(function() 
                 {
                    $(this).hide();
                    $(this).siblings('.expanded').show();
                    $.get('<?php echo url_for("specimensearch/individualTree?id=".$specimen->getSpecRef()) ;?>',function (html){
                           $('#container_individual_<?php echo $specimen->getSpecRef();?>').html(html).slideDown();
                           });
                 });  
                 $('tr.rid_<?php echo $specimen->getSpecRef(); ?> img.expanded').click(function() 
                 {
                    $(this).hide();
                    $(this).siblings('.collapsed').show();
                    $('#container_individual_<?php echo $specimen->getSpecRef();?>').slideUp();
                 });
                </script>
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

  o = {"dropShadows":false, "autoArrows":true, "firstOnClick":true, "delay":400};
  $('ul.column_menu').supersubs().superfish(o);

  $('ul.column_menu > li > ul > li').each(function(){
    hide_or_show($(this));
  });
  initIndividualColspan() ;
  $("ul.column_menu > li > ul > li").click(function(){
    update_list($(this));
    hide_or_show($(this));
    store_list($(this).parent(), '<?php echo url_for('specimensearch/saveCol');?>')
  });

  /**PIN management **/
  $('.spec_results .pin_but').click(function(){
    if($(this).hasClass('pin_on'))
    {
      $(this).parent().find('.pin_off').removeClass('hidden'); 
      $(this).addClass('hidden') ;
      pin_status = 0;
    }
    else
    {
      $(this).parent().find('.pin_on').removeClass('hidden');
      $(this).addClass('hidden') ;
      pin_status = 1;
    }
    rid = getIdInClasses($(this).closest('tr'));
    $.get('<?php echo url_for('savesearch/pin');?>/id/' + rid + '/status/' + pin_status,function (html){
    });
  });

  <?php if($is_specimen_search):?>
    $('.spec_results .pin_del').click(function(){
      rid = getIdInClasses($(this).closest('tr'));
      $.get('<?php echo url_for('savesearch/removePin?search='.$is_specimen_search);?>/id/' + rid ,function (html){
        $('.rid_'+rid).closest('tbody').remove();
      });
    });
  <?php endif;?>

});
</script> 
