<table class="catalogue_table_view">
  <thead style="<?php echo ($accompanying->count()?'':'display: none;');?>">
    <tr>
      <th>
        <?php echo __('Type'); ?>
      </th>
      <th>
        <?php echo __('Form'); ?>
      </th>
      <th colspan="2">
        <?php echo __('Quantity'); ?>
      </th>
      <th></th>   
    </tr>
  </thead>
  <?php foreach($accompanying as $val):?>
  <tr>
    <td><?php echo $val->getAccompanyingType() ; ?></td>
    <td><?php echo $val->getForm();?></td>
    <td><?php echo $val->getQuantity();?><?php echo $val->getUnit();?></td> 
    <td>
      <?php if ($val->getAccompanyingType()=="mineral") : ?>     
        <a href="<?php echo url_for('mineral/view?id='.$val->getMineralRef()) ; ?>"><?php echo $val->Mineral->getName() ; ?></a>
      <?php else : ?>
        <a href="<?php echo url_for('taxonomy/view?id='.$val->getTaxonRef()) ; ?>"><?php echo $val->Taxonomy->getName(); ?></a>
      <?php endif ; ?>
    </td>
  </tr>
  <?php endforeach;?>
</table>
