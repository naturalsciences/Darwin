<table  class="property_values">
  <thead style="<?php echo ($form['SpecimensAccompanying']->count() || $form['newSpecimensAccompanying']->count())?'':'display: none;';?>">
    <tr>
      <th>
        <?php echo __('Type'); ?>
      </th>
      <th>
        <?php echo __('Form'); ?>
      </th>
      <th colspan="2">
        <?php echo __('Quantity'); ?>
      </th>
      <th>
	<?php echo $form['accompanying'];?>
      </th>
    </tr>
  </thead>
    <?php $retainedKey = 0;?>
    <?php foreach($form['SpecimensAccompanying'] as $form_value):?>
      <?php include_partial('specimen/specimens_accompanying', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
    <?php foreach($form['newSpecimensAccompanying'] as $form_value):?>
      <?php include_partial('specimen/specimens_accompanying', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
  <tfoot>
    <tr>
      <td colspan='5'>
        <div class="add_code">
          <a href="<?php echo url_for('specimen/addSpecimensAccompanying'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_accompanying"><?php echo __('Add element');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
<script  type="text/javascript">
$(document).ready(function () {

    $('#add_accompanying').click(function()
    {
        parent = $(this).closest('table.property_values');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ (0+$('.property_values tbody').length),
          success: function(html)
          {
            $(parent).append(html);
            $(parent).find('thead:hidden').show();
          }
        });
        return false;
    });

});
</script>