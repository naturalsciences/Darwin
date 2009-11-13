<?php if(isset($expeditions) && $expeditions->count() != 0):?>
  <table>
    <thead>
      <tr>
        <th>&nbsp;</th>
        <th><?php echo __('Name');?></th>
        <th><?php echo __('From');?></th>
        <th><?php echo __('To');?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($expeditions as $expedition):?>
        <tr class="rid_<?php echo $expedition->getId();?>">
          <td>&nbsp;</td>
          <td><?php echo $expedition->getName();?></td>
          <td><?php echo $expedition->getExpeditionFromDate();?></td>
          <td><?php echo $expedition->getExpeditionToDate();?></td>
          <td>&nbsp;</td>
        <?php //echo link_to('(e)','taxonomy/edit?id='.$taxa->getId());?>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
<?php else:?>
  <?php echo __('No Expedition Matching');?>
<?php endif;?>