<?php if(isset($expeditions) && $expeditions->count() != 0 && isset($orderBy) && isset($orderDir)):?>
  <table class="results">
    <thead>
      <tr>
        <th>&nbsp;</th>
        <th>
            <a class="sort" href="<?php echo url_for('expedition/search?orderby=name'.(($orderBy=='name' && $orderDir=='asc')?'&orderdir=desc':''));?>">
            <?php echo __('Name');?>
            <?php if($orderBy=='name'):?>
              <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
            <?php endif; ?>
            </a>
        </th>
        <th class="datesNum">
            <a class="sort" href="<?php echo url_for('expedition/search?orderby=expedition_from_date'.(($orderBy=='expedition_from_date' && $orderDir=='asc')?'&orderdir=desc':''));?>">
            <?php echo __('From');?>
            <?php if($orderBy=='expedition_from_date'):?>
              <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
            <?php endif; ?>
            </a>
        </th>
        <th class="datesNum">
            <a class="sort" href="<?php echo url_for('expedition/search?orderby=expedition_to_date'.(($orderBy=='expedition_to_date' && $orderDir=='asc')?'&orderdir=desc':''));?>">
            <?php echo __('To');?>
            <?php if($orderBy=='expedition_to_date'):?>
              <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
            <?php endif; ?>
            </a>
        </th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($expeditions as $expedition):?>
        <tr id="rid_<?php echo $expedition->getId(); ?>">
          <td>&nbsp;</td>
          <td><?php echo $expedition->getName();?></td>
          <td class="datesNum"><?php echo $expedition->getExpeditionFromDateMasked();?></td>
          <td class="datesNum"><?php echo $expedition->getExpeditionToDateMasked();?></td>
          <td class="edit">
            <?php if(! isset($is_choose)):?>
                <?php echo link_to(image_tag('edit.png'),'expedition/edit?id='.$expedition->getId());?>
            <?php endif;?>
          </td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
<?php else:?>
  <?php echo __('No Expedition Matching');?>
<?php endif;?>