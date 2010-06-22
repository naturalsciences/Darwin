<table>
  <tbody>
    <?php if($form['gtu_ref']->hasError()):?>
      <tr>
        <td colspan="2"><?php echo $form['gtu_ref']->renderError(); ?><td>
      </tr>
    <?php endif; ?>
    <tr>
      <th>
        <?php echo $form['gtu_ref']->renderLabel() ?>
      </th>
      <td>
        <?php echo $form['gtu_ref']->render() ?>
      </td>
    </tr>
    <?php if($form['station_visible']->hasError()):?>
      <tr>
        <td colspan="2"><?php echo $form['station_visible']->renderError(); ?><td>
      </tr>
    <?php endif; ?>
    <tr>
      <th>
        <?php echo $form['station_visible']->renderLabel() ?>
      </th>
      <td>
        <?php echo $form['station_visible']->render() ?>
      </td>
    </tr>
  </tbody>
<script language="javascript" type="text/javascript"> 
$(document).ready(function () {
    $('#specimen_gtu_ref').change(function()
    {
      $("#specimen_gtu_ref_name").html(trim(ref_element_name));
    });

});
</script>
</table>