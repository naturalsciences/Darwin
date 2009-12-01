<?php if(isset($expeditions) && $expeditions->count() != 0):?>
  <table class="results">
    <thead>
      <tr>
        <th>&nbsp;</th>
        <th><?php echo __('Name');?></th>
        <th class="datesNum"><?php echo __('From');?></th>
        <th class="datesNum"><?php echo __('To');?></th>
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
            <?php if(! $is_choose):?>
                <?php echo link_to(__('(e)'),'expedition/edit?id='.$expedition->getId());?>
            <?php endif;?>
          </td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
<?php else:?>
  <?php echo __('No Expedition Matching');?>
<?php endif;?>