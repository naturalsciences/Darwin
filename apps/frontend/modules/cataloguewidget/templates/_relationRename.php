<?php slot('widget_title',__('Renamed'));  ?>
<table class="catalogue_table">
  <thead>
    <th><?php echo __('Renamed to');?></th>
    <th></th>
  </thead>
  <tbody>
  <?php foreach($relations as $renamed):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Rename');?>" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&id='.$eid.'&relid='.$renamed['id']) ?>"><?php echo $renamed['ref_item']->getNameWithFormat()?></a>
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
<?php if(count($relations) == 0 ):?><?php echo image_tag('add_green.png');?><a title="<?php echo __('Rename');?>" class="link_catalogue" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&id='.$eid);?>"><?php else:?><?php echo image_tag('add_grey.png');?><span class='add_not_allowed'><?php endif;?><?php echo __('Add');?><?php if(count($relations) == 0 ):?></a><?php else:?></span><?php endif;?>