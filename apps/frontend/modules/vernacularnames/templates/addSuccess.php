<div id="property_screen">
<?php //echo $form;?>
<form class="edition" action="<?php echo url_for('vernacularnames/add?table='.$sf_request->getParameter('table').'&id='.$sf_request->getParameter('id') . ($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() ) );?>" method="post" id="property_form">
<?php echo $form['referenced_relation'];?>
<?php echo $form['record_id'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['community']->renderLabel();?></th>
      <td>
        <?php echo $form['community']->renderError(); ?>
        <?php echo $form['community'];?>
      </td>
    </tr>
  </tbody>
</table>
<table class="encoding proprety_values">
  <thead>
    <tr>
      <th><label><?php echo __('Vernacular name');?></label></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($form['VernacularNames'] as $form_value):?>
      <?php include_partial('vernacular_names_values', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newVal'] as $form_value):?>
      <?php include_partial('vernacular_names_values', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
</table>
<div class='add_value'>
  <a href="<?php echo url_for('vernacularnames/addValue'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_prop_value">Add Value</a>
</div>
<table class="bottom_actions">
  <tfoot>
    <tr>
      <td>
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
          <button id="delete"><?php echo __('Delete');?></button>
        <?php endif;?> 
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>
</form>

<script  type="text/javascript">
  $(document).ready(function () {
    $("#delete").click(function()
      {
	if(confirm('<?php echo __('Are you sure?');?>'))
	{
	  hideForRefresh($('#property_screen'));
	  $.ajax({
	    url: '<?php echo url_for('vernacularnames/delete?id='.$form->getObject()->getId())?>',
	    success: function(html){
	      if(html == "ok" )
	      {
		$('.qtip-button').click();
	      }
	      else
	      {
		addError(html);
	      }
	    },
	  });
	}
	return false;
      });

    $('.clear_prop').live('click',function (){
      parent = $(this).closest('tr');
      nvalue='';
      $(parent).find('input').val(nvalue);
      $(parent).hide();
    });

    $('form#property_form').submit(function () {
      $('form#property_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#property_screen'));
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    if(html == 'ok')
	    {
	      $('.qtip-button').click();
	    }
	    $('form#property_form').parent().before(html).remove();
	  }
      });
      return false;
    });

    $('#add_prop_value').click(function () {
	$.ajax({
	  type: "GET",
	  url: $(this).attr('href')+ (0+$('.proprety_values tbody tr').length),
	  success: function(html){
	    $('.proprety_values tbody').append(html);
	  }
	});
	return false;
    });
  });
</script>
</div>
