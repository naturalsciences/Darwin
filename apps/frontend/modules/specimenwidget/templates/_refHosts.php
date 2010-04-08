<table>
  <tbody>
    <?php if($form['host_specimen_ref']->hasError()):?>
      <tr>
        <td colspan="2"><?php echo $form['host_specimen_ref']->renderError(); ?><td>
      </tr>
    <?php endif; ?>
    <tr>
      <th>
        <?php echo $form['host_specimen_ref']->renderLabel(); ?>
      </th>
      <td>
        <?php echo $form['host_specimen_ref']->render(); ?>
      </td>
    </tr>
    <?php if($form['host_taxon_ref']->hasError()):?>
      <tr>
        <td colspan="2"><?php echo $form['host_taxon_ref']->renderError(); ?><td>
      </tr>
    <?php endif; ?>
    <tr>
      <th>
        <?php echo $form['host_taxon_ref']->renderLabel() ?>
      </th>
      <td>
        <?php echo $form['host_taxon_ref']->render() ?>
      </td>
    </tr>
  </tbody>
</table>