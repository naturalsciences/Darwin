<h1>Collecting methodss List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Method</th>
      <th>Method indexed</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($collecting_methodss as $collecting_methods): ?>
    <tr>
      <td><a href="<?php echo url_for('collecting_methods/edit?id='.$collecting_methods->getId()) ?>"><?php echo $collecting_methods->getId() ?></a></td>
      <td><?php echo $collecting_methods->getMethod() ?></td>
      <td><?php echo $collecting_methods->getMethodIndexed() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('collecting_methods/new') ?>">New</a>
