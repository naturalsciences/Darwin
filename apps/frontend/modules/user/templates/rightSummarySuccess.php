<?php if($rights->count() > 0) : ?>
<table class="summary">
  <thead>
    <tr>
      <th><?php echo ('Collection') ; ?></th>
      <th><?php echo ('Right') ; ?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($rights as $right) : ?>
    <tr>
      <td><?php echo $right->Collections->getName() ; ?></td>
      <td><?php echo $summary->getRaw($right->getDbUserType()) ;?></td>
    </tr>
  <?php endforeach ; ?>
  </tbody>
</table>
<?php else : ?>
  <?php echo __("You can view only public collections"); ?>
<?php endif ; ?>
