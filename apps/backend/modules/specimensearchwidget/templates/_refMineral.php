<table>
  <thead>
    <tr>
      <th><?php echo $form['mineral_name']->renderLabel();?></th>
      <th><?php echo $form['mineral_level_ref']->renderLabel(__('Level'));?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $form['mineral_name'];?></td>
      <td><?php echo $form['mineral_level_ref'];?></td>
    </tr>
    <tr>
      <td><?php echo $form['mineral_relation'];?></td>
      <td><?php echo $form['mineral_item_ref'];?></td>
    </tr>
  </tbody>
</table>
