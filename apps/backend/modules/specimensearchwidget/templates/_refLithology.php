<table>
  <thead>
    <tr>
      <th><?php echo $form['lithology_name']->renderLabel();?></th>
      <th><?php echo $form['lithology_level_ref']->renderLabel(__('Level'));?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $form['lithology_name'];?></td>
      <td><?php echo $form['lithology_level_ref'];?></td>
    </tr>
    <tr>
      <td><?php echo $form['lithology_relation'];?></td>
      <td><?php echo $form['lithology_item_ref'];?></td>
    </tr>
  </tbody>
</table>
