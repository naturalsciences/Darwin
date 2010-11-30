<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Renamed to');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($relations as $renamed):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Rename');?>" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&rid='.$eid.'&id='.$renamed['id']) ?>"><?php echo $renamed['ref_item']->getNameWithFormat(ESC_RAW)?></a>
      <?php echo image_tag('info-green.png',"title=info class=info");?>
      <p class="tree">
      </p>
      <script type="text/javascript">
       $('table.catalogue_table').find('.info').click(function() 
       {   
         item_row = $(this).closest('td') ;
         if(item_row.find('.tree').is(":hidden"))
         {
           $.get('<?php echo url_for('catalogue/tree?table=taxonomy&id='.$renamed['record_id_2']) ; ?>',function (html){
             item_row.find('.tree').html(html).slideDown();
             });
         }
         item_row.find('.tree').slideUp();
       });
      </script>        
    </td>
    <td class="widget_row_delete">
      <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_relationships&id='.$renamed['id']);?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<br />
<?php if(count($relations) == 0 ):?><?php echo image_tag('add_green.png');?><a title="<?php echo __('Rename');?>" class="link_catalogue" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&rid='.$eid);?>"><?php else:?><?php echo image_tag('add_grey.png');?><span class='add_not_allowed'><?php endif;?><?php echo __('Add');?><?php if(count($relations) == 0 ):?></a><?php else:?></span><?php endif;?>
