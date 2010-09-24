<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<?php $sFormClass = get_class($form->getEmbeddedForm('MassActionForm'));?>
<?php if(isset($mAction) && $mAction == 'collection_ref'):?>
  <table id="sub_form_<?php echo $mAction;?>">
    <tr>
      <th>
        <?php echo $form['MassActionForm']['collection_ref']['collection_ref']->renderLabel();?>
      </th>
    </tr>
    <tr>
      <td>
        <?php echo $form['MassActionForm']['collection_ref']['collection_ref']->renderError();?>
        <?php echo $form['MassActionForm']['collection_ref']['collection_ref']->render(array('class' => 'inline'));?>
      </td>
    <tr>
  </table>

  <script  type="text/javascript">
  $(document).ready(function () {
   $('#mass_action_MassActionForm_collection_ref_collection_ref').change(function ()
    {
      changeSubmit(true);
    });
  });
  </script>
<?php elseif($sFormClass == 'sfForm'):?>
  <?php echo $form['MassActionForm'];?>
<?php else:?>
  <div class="warning"><?php echo __("Houston, We've Got a Problem");?></div>
<?php endif;?>