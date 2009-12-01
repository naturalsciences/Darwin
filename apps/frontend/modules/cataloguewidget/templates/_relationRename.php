<?php slot('widget_title',__('Renamed'));  ?>
<table>
  <?php foreach($relations as $renamed):?>
  <tr>
    <th>
    Renamed to
    </th>
    <td>
      <a class="link_catalogue" title="<?php echo __('Rename');?>" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&id='.$eid.'&relid='.$renamed[0]) ?>"><?php echo $renamed[5]//Rec Name?></a>
    </td>
  </tr>
  <?php endforeach;?>
</table>
  <?php if(count($relations) == 0 ):?>
    <br />
    <?php echo image_tag('add_green.png');?><a title="<?php echo __('Rename');?>" class="link_catalogue" href="<?php echo url_for('catalogue/relation?type=rename&table='.$table.'&id='.$eid) ?>"><?php echo __('Add');?></a>
  <?php endif;?>