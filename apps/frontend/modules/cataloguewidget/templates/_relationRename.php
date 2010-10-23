<table class="catalogue_table<?php echo($sf_user->isA(Users::REGISTERED_USER)?'_view':'') ;?>">
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
      <?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>     
      <a class="link_catalogue" title="<?php echo __('Rename');?>" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&rid='.$eid.'&id='.$renamed['id']) ?>"><?php echo $renamed['ref_item']->getNameWithFormat()?></a>
      <?php else : ?>
        <?php echo $renamed['ref_item']->getNameWithFormat()?>
      <?php endif ; ?>
    </td>
    <td class="widget_row_delete">
      <?php if($sf_user->isAtLeast(Users::ENCODER)) : ?> 
      <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_relationships&id='.$renamed['id']);?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
      <?php endif ; ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<br />
<?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>
<?php if(count($relations) == 0 ):?><?php echo image_tag('add_green.png');?><a title="<?php echo __('Rename');?>" class="link_catalogue" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&rid='.$eid);?>"><?php else:?><?php echo image_tag('add_grey.png');?><span class='add_not_allowed'><?php endif;?><?php echo __('Add');?><?php if(count($relations) == 0 ):?></a><?php else:?></span><?php endif;?>
<?php endif ; ?>
