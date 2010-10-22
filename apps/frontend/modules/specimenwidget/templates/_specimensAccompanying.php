<?php $read_only = (isset($view)&&$view)?true:false ; ?>
<table class="<?php echo($read_only?'catalogue_table_view':'property_values');?>" id="identifications">
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
        <th></th>   
    </tr>
  </thead>
    <?php $retainedKey = 0;?>
    <?php foreach($form['SpecimensAccompanying'] as $form_value):?>
      <?php if($read_only) : ?>
        <td><?php echo $form_value['accompanying_type']->getValue() ; ?></td>
        <td><?php echo $form_value['form']->getValue();?></td>
        <td><?php echo $form_value['quantity']->getValue();?></td>
        <td><?php echo $form_value['unit']->getValue();?></td> 
        <td>
          <?php if ($form_value['accompanying_type']->getValue()=="mineral") : ?>     
            <a href="<?php echo url_for('mineral/view?id='.$form_value['mineral_ref']->getValue()) ; ?>"><?php echo $form_value['mineral_ref']->renderLabelName() ; ?></a>
          <?php else : ?>
            <a href="<?php echo url_for('taxonomy/view?id='.$form_value['taxon_ref']->getValue()) ; ?>"><?php echo $form_value['taxon_ref']->renderLabelName(); ?></a>
          <?php endif ; ?>
        </td>
      <?php else : ?>    
        <?php include_partial('specimen/specimens_accompanying', array('form' => $form_value, 'rownum'=>$retainedKey));?>
        <?php $retainedKey = $retainedKey+1;?>
      <?php endif ; ?>
    <?php endforeach;?>
    <?php foreach($form['newSpecimensAccompanying'] as $form_value):?>
      <?php include_partial('specimen/specimens_accompanying', array('form' => $form_value, 'rownum'=>$retainedKey));?>
      <?php $retainedKey = $retainedKey+1;?>
    <?php endforeach;?>
<?php if(!$read_only) : ?>    
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
          url: $(this).attr('href')+ (0+$(parent).find('tbody').length),
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
<?php else : ?>
</table>
<?php endif ; ?>
