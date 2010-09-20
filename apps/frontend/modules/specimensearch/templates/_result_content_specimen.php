    <td rowspan="2">
      <?php if($is_specimen_search):?>
        <input type="checkbox" value="<?php echo $specimen->getSpecRef();?>" class="spec_selected"/>
      <?php endif;?>
    </td>
    <td rowspan="2">
      <?php if($source != 'part'):?>
        <?php echo image_tag('blue_expand.png', array('alt' => '+', 'class'=> 'tree_cmd_td collapsed')); ?>
        <?php echo image_tag('blue_expand_up.png', array('alt' => '-', 'class'=> 'tree_cmd_td expanded')); ?>
      <?php endif;?>
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
      <?php endif ; ?>
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
          <?php if($specimen->getGtuCountryTagValue() != ""): ?>
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
      <?php endif ; ?>
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
      <?php endif ; ?>
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
      <?php endif ; ?>
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
      <?php endif ; ?>
    </td>
    <td class="col_expedition">
      <?php if($specimen->getExpeditionRef() > 0) : ?>
        <a href="<?php echo url_for('expedition/edit?id='.$specimen->getExpeditionRef());?>"><?php echo $specimen->getExpeditionName();?></a>
      <?php endif ; ?>
    </td> 
    <td class="col_count">
        <?php if($specimen->getSpecimenCountMax()==$specimen->getSpecimenCountMin()):?>
          <?php echo $specimen->getSpecimenCountMax();?>
        <?php else:?>
           <?php echo $specimen->getSpecimenCountMin();?> -  <?php echo $specimen->getSpecimenCountMax();?>
        <?php endif;?>
    </td>