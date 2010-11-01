<table class="catalogue_table_view">
  <tbody>
    <tr>
      <th>
        <?php echo __('Relationship') ?>
      </th>
      <td>
        <?php echo $spec->getHostRelationship() ?>
      </td>
    </tr>
    <tr>
      <th>
        <?php echo __('Host Taxon') ?>
      </th>
      <td>
        <?php echo $spec->HostTaxon->getName() ?>
      </td>
    </tr>
    <tr id="host_specimen_ref">
      <th>
        <?php echo __('Host specimen') ?>
      </th>
      <td>
        <?php echo $spec->HostSpecimen->getName() ?>
      </td>
    </tr>
  </tbody>
</table>
