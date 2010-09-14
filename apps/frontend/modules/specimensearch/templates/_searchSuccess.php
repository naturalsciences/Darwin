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
            <?php
            $cols = array(
              'category' => __("Category"),
              'collection' => __("Collection"),
              'taxon' => __("Taxon"),
              'type' => __("Type ?"),
              'gtu' => __("Sampling location"),
              'codes' => __("Codes"),
              'chrono' => __("Chronostratigraphic unit"),
              'litho' => __("Lithostratigraphic unit"),
              'lithologic' => __("Lithologic unit"),
              'mineral' => __("Mineralogic"),
              'expedition' => __("Expedition"),
              'count' => __("Counts"));
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
            <th></th>
            <th>
               <?php echo image_tag('white_pin_off.png', array('class'=>'top_pin_but pin_off','alt' =>  __('Un-Save this result'))) ; ?>
               <?php echo image_tag('white_pin_on.png', array('class'=>'top_pin_but pin_on', 'alt' =>  __('Save this result'))) ; ?>
            </th>
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
            <th class="col_codes">
              <?php echo __('Codes');?>
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
                <?php if($is_specimen_search):?>
                  <input type="checkbox" value="<?php echo $specimen->getSpecRef();?>" class="spec_selected"/>
                <?php endif;?>
              </td>
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
              <td class="col_category">
                <?php if($specimen->getCategory() == 'physical' || $specimen->getCategory() == 'mixed' ):?>
                  <?php echo image_tag('sp_in.png', array('alt' => __('Physical'), 'title'=> __('Physical')));?>
                <?php endif;?>
                <?php if($specimen->getCategory() == 'mixed' ):?>
                 <?php echo __('+');?>
                <?php endif;?>
                <?php if($specimen->getCategory() == ''  || $specimen->getCategory() == 'mixed' ):?>
                  <?php echo image_tag('blue_eyel.png', array('alt' => __('Other'), 'title'=> __('Other')));?>
                <?php endif;?>
              </td>
              <td  class="col_collection">
                <?php if($specimen->getCollectionRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=collection_".$specimen->getSpecRef()."_info");?>
                  <a href="<?php echo url_for('collection/edit?id='.$specimen->getCollectionRef());?>"><?php echo $specimen->getCollectionName();?></a>
                  <div id="collection_<?php echo $specimen->getSpecRef();?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#collection_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#collection_<?php echo $specimen->getSpecRef();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=collections&id=".$specimen->getCollectionRef()) ;?>',function (html){
                           item_row.find('#collection_<?php echo $specimen->getSpecRef();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#collection_<?php echo $specimen->getSpecRef();?>_tree').slideUp();
                     });
                  </script>                  
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
                  <?php if($specimen->getGtuTagValuesIndexed() != "") : ?>                  
                    <?php echo image_tag('info.png',"title=info class=info id=gtu_ctr_".$specimen->getSpecRef()."_info");?>                    
                    <a href="<?php echo url_for('gtu/edit?id='.$specimen->getGtuRef()) ;?>"><?php echo $specimen->getGtuCode();?></a>  
                    <div class="general_gtu">
                    <?php if($specimen->getGtuCountryTagValue() != "") : ?>                    
                      <strong><?php echo __('Country');?> :</strong>
                      <?php echo $specimen->getCountryTags();?>
                    <?php endif ; ?>
                    </div>
                    <div id="gtu_<?php echo $specimen->getSpecRef();?>_details" style="display:none;"></div>
                  <?php else : ?>
                    <a href="<?php echo url_for('gtu/edit?id='.$specimen->getGtuRef());?>"><?php echo $specimen->getGtuCode();?></a>
                  <?php endif ; ?>                 
                   <script type="text/javascript">
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
                  </script>
                <?php endif ; ?>          
              </td> 
              <td class="col_codes">     
                <?php foreach($codes as $key=>$code):?>
                  <?php if ($code->getRecordId() === $specimen->getSpecRef()) : ?>
                    <?php if ($code->getCodeCategory() == 'main' ) : ?>
                      <?php echo ('<b>'.$code->getCodePrefix().$code->getCodePrefixSeparator().$code->getCode().$code->getCodeSuffixSeparator().
                      $code->getCodeSuffix()."</b><br />") ; ?>
                    <?php else : ?>
                      <?php echo ($code->getCodePrefix().$code->getCodePrefixSeparator().$code->getCode().$code->getCodeSuffixSeparator().
                      $code->getCodeSuffix()."<br />") ; ?>  
                    <?php endif ; ?>
                  <?php endif ; ?>
                <?php endforeach; ?>
              </td>                
              <td  class="col_chrono">
                <?php if($specimen->getChronoRef() > 0) : ?>              
                  <?php echo image_tag('info.png',"title=info class=info id=chrono_".$specimen->getSpecRef()."_info");?>
                  <a href="<?php echo url_for('chronostratigraphy/edit?id='.$specimen->getChronoRef());?>"><?php echo $specimen->getChronoName();?></a>
                  <div id="chrono_<?php echo $specimen->getSpecRef();?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#chrono_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#chrono_<?php echo $specimen->getSpecRef();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=chronostratigraphy&id=".$specimen->getChronoRef()) ;?>',function (html){
                           item_row.find('#chrono_<?php echo $specimen->getSpecRef();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#chrono_<?php echo $specimen->getSpecRef();?>_tree').slideUp();
                     });
                  </script>  
                <?php endif ; ?>&nbsp;                              
              </td>
              <td  class="col_litho">
                <?php if($specimen->getLithoRef() > 0) : ?>              
                  <?php echo image_tag('info.png',"title=info class=info id=litho_".$specimen->getSpecRef()."_info");?>                
                  <a href="<?php echo url_for('lithostratigraphy/edit?id='.$specimen->getLithoRef());?>"><?php echo $specimen->getLithoName();?></a>
                  <div id="litho_<?php echo $specimen->getSpecRef();?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#litho_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#litho_<?php echo $specimen->getSpecRef();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=lithostratigraphy&id=".$specimen->getLithoRef()) ;?>',function (html){
                           item_row.find('#litho_<?php echo $specimen->getSpecRef();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#litho_<?php echo $specimen->getSpecRef();?>_tree').slideUp();
                     });
                  </script> 
                <?php endif ; ?>&nbsp;                
              </td> 
              <td class="col_lithologic">
                <?php if($specimen->getLithologyRef() > 0) : ?>              
                  <?php echo image_tag('info.png',"title=info class=info id=lithologic_".$specimen->getSpecRef()."_info");?>                                
                  <a href="<?php echo url_for('lithology/edit?id='.$specimen->getLithologyRef());?>"><?php echo $specimen->getLithologyName();?></a>
                  <div id="lithologic_<?php echo $specimen->getSpecRef();?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#lithologic_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#lithologic_<?php echo $specimen->getSpecRef();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=lithology&id=".$specimen->getLithologyRef()) ;?>',function (html){
                           item_row.find('#lithologic_<?php echo $specimen->getSpecRef();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#lithologic_<?php echo $specimen->getSpecRef();?>_tree').slideUp();
                     });
                  </script> 
                <?php endif ; ?>&nbsp;
              </td>
              <td class="col_mineral">
                <?php if($specimen->getMineralRef() > 0) : ?>
                  <?php echo image_tag('info.png',"title=info class=info id=mineral_".$specimen->getSpecRef()."_info");?>                
                  <a href="<?php echo url_for('mineralogy/edit?id='.$specimen->getMineralRef());?>"><?php echo $specimen->getMineralName();?></a>
                  <div id="mineral_<?php echo $specimen->getSpecRef();?>_tree" class="tree"></div>
                  <script type="text/javascript">
                     $('#mineral_<?php echo $specimen->getSpecRef();?>_info').click(function() 
                     {
                       item_row=$(this).closest('tr');
                       if(item_row.find('#mineral_<?php echo $specimen->getSpecRef();?>_tree').is(":hidden"))
                       {
                         $.get('<?php echo url_for("catalogue/tree?table=mineralogy&id=".$specimen->getMineralRef()) ;?>',function (html){
                           item_row.find('#mineral_<?php echo $specimen->getSpecRef();?>_tree').html(html).slideDown();
                           });
                       }
                       $('#mineral_<?php echo $specimen->getSpecRef();?>_tree').slideUp();
                     });
                  </script> 
                <?php endif ; ?>&nbsp;
              </td>              
              <td class="col_expedition">
                <?php if($specimen->getExpeditionRef() > 0) : ?>
                  <a href="<?php echo url_for('expedition/edit?id='.$specimen->getExpeditionRef());?>"><?php echo $specimen->getExpeditionName();?></a>
                <?php endif ; ?>&nbsp;
              </td> 
              <td class="col_count">
                  <?php echo $specimen->getSpecimenCountMax();?>
              </td>
              <td rowspan="2">
                  <?php echo link_to(image_tag('edit.png', array("title" => __("Edit"))),'specimen/edit?id='.$specimen->getSpecRef());?>
                  <?php echo link_to(image_tag('duplicate.png', array("title" => __("Duplicate"))),'specimen/new?duplicate_id='.$specimen->getSpecRef(), array('class' => 'duplicate_link'));?>
              </td>
            </tr>
            <tr id="tr_individual_<?php echo $specimen->getSpecRef();?>" class="ind_row">
              <td colspan="6"> 
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
  $('body').catalogue({},link=$('div.check_right').find('a.hidden').attr('href')); 
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
    $.get('<?php echo url_for('savesearch/pin');?>/id/' + rid + '/status/' + pin_status,function (html){});
  });

  if($('.spec_results tbody .pin_on').not('.hidden').length == $('.spec_results tbody .pin_on').length)
  {
      $('.top_pin_but').parent().find('.pin_on').removeClass('hidden');
      $('.top_pin_but').parent().find('.pin_off').addClass('hidden') ;
  }
  else
  {
      $('.top_pin_but').parent().find('.pin_off').removeClass('hidden');
      $('.top_pin_but').parent().find('.pin_on').addClass('hidden') ;
  }
  
  $('.spec_results .top_pin_but').click(function(){
    /** Multiple pin behavior ***/
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
    pins = '';
    $('.spec_results tbody tr').not('.ind_row').each(function(){
      rid = getIdInClasses($(this));
      if(pins == '')
        pins = rid;
      else
        pins += ','+rid;
    });

    if(pin_status == 0)
    {
        $('.spec_results tbody tr .pin_off').removeClass('hidden');
        $('.spec_results tbody tr .pin_on').addClass('hidden') ;
    }
    else
    {
        $('.spec_results tbody tr .pin_off').addClass('hidden');
        $('.spec_results tbody tr .pin_on').removeClass('hidden') ;
    }
    $.get('<?php echo url_for('savesearch/pin');?>/mid/' + pins + '/status/' + pin_status,function (html){});
  }); 

});
</script> 
