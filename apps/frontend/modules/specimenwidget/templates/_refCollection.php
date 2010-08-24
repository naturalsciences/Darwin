<?php slot('widget_mandatory_refCollection',true);  ?>
<table>
  <tbody>
    <tr>
      <td colspan="2"><?php echo $form['collection_ref']->renderError() ?><td>
    </tr>  
    <tr>
      <td colspan="2"><?php echo $form['collection_ref']->render() ?></td>
    </tr>
    <tr>
      <td colspan="2"><?php echo $form['category']->renderError() ?><td>
    </tr>  
    <tr>
      <th><?php echo $form['category']->renderLabel("Specimen category") ?></th>
      <td><?php echo $form['category']->render() ?></td>
    </tr>
  </tbody>
</table>  



