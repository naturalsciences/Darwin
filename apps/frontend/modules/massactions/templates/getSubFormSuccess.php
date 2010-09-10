<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<?php $sFormClass = get_class($form->getEmbeddedForm('MassActionForm'));?>
<?php if($sFormClass == 'MaCollectionRefForm'):?>
  <table>
    <tr>
      <th><?php echo $form['MassActionForm']['collection_ref']->renderLabel();?></th>
    </tr>
    <tr>
      <td><?php echo $form['MassActionForm']['collection_ref']->render(array('class' => 'inline'));?></td>
    <tr>
  </table>
<?php else:?>
  <div class="warning"><?php echo __("Houston, We've Got a Problem");?></div>
<?php endif;?>