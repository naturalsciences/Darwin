<div class="page">
<h1>Institutions List</h1>

<table class="results">
  <thead>
    <tr>
      <th>Id</th>
      <th>Sub type</th>
      <th>Formated name</th>
      <th>Additional names</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($institutions as $institution): ?>
    <tr>
      <td><a href="<?php echo url_for('institution/edit?id='.$institution->getId()) ?>"><?php echo $institution->getId() ?></a></td>
      <td><?php echo $institution->getSubType() ?></td>
      <td><?php echo $institution->getFormatedName() ?></td>
      <td><?php echo $institution->getAdditionalNames() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <br /><br />
  <div class='new_link'>
    <a href="<?php echo url_for('institution/new') ?>">New</a>
  </div>

</div>
