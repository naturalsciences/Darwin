<h1>Collecting toolss List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Tool</th>
      <th>Tool indexed</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($collecting_toolss as $collecting_tools): ?>
    <tr>
      <td><a href="<?php echo url_for('collecting_tools/edit?id='.$collecting_tools->getId()) ?>"><?php echo $collecting_tools->getId() ?></a></td>
      <td><?php echo $collecting_tools->getTool() ?></td>
      <td><?php echo $collecting_tools->getToolIndexed() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('collecting_tools/new') ?>">New</a>
