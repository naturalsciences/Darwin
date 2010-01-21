<?php slot('widget_title',__('Recombination'));  ?>
<table class="catalogue_table">
  <thead>
    <th><?php echo __('Combination of');?></th>
    <th></th>
  </thead>
  <tbody>
  <?php foreach($relations as $renamed):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Recombination');?>" href="<?php echo url_for('catalogue/relation?type=recombined&table='.$table.'&id='.$eid.'&relid='.$renamed['id']) ?>"><?php echo $renamed['ref_item']->getNameWithFormat()?></a>
    </td>
    <td class="widget_row_delete">
      <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelation?relid='.$renamed['id']);?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
  <?php if(count($relations) <=1 ):?>
    <br />
    <?php echo image_tag('add_green.png');?><a title="<?php echo __('Recombination');?>" class="link_catalogue" href="<?php echo url_for('catalogue/relation?type=recombined&table='.$table.'&id='.$eid) ?>"><?php echo __('Add');?></a>
  <?php endif;?>