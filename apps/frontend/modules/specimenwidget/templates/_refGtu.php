<table>
  <tbody>
    <?php if($form['gtu_ref']->hasError()):?>
      <tr>
        <td colspan="2"><?php echo $form['gtu_ref']->renderError(); ?><td>
      </tr>
    <?php endif; ?>
    <tr>
      <th><label><?php echo __('Sampling location code');?></label></th>
      <td id="specimen_gtu_ref_code"></td>
    <tr>
      <th class="top_aligned">
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
    
    el_name = $("#specimen_gtu_ref_name b");
    if(el_name.length)
    {
      $("#specimen_gtu_ref_code").html(el_name.html());
      el_name.remove();
    }
  
    $('#specimen_gtu_ref').change(function()
    {
      $("#specimen_gtu_ref_name").html(trim(ref_element_name));
      $("#specimen_gtu_ref_code").html(ref_element_code);
    });
   

});
</script>
</table>