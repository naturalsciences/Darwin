<div id="catalogue_keywords">
      <table>
      <thead>
	<tr>
	  <th><?php echo __('Type');?></th>
	  <th><?php echo __('Keyword');?></th>
	  <th></th>
	</tr>
      </thead>
      <tbody>
	  <?php foreach($form['ClassificationKeywords'] as $keyword):?>
	    <?php include_partial('catalogue/nameValue', array('form' => $keyword));?>
	  <?php endforeach;?>
	  <?php foreach($form['newVal'] as $keyword):?>
	    <?php include_partial('catalogue/nameValue', array('form' => $keyword));?>
	  <?php endforeach;?>
      </tbody>
      </table>
</div>


<script language="javascript">
$(document).ready(function () {

  $('.name_tags li').click(function()
  {
    var tag_value = returnText($('#<?php echo $field_name;?>'));
    if( trim(tag_value) != '')
    {
      $.ajax(
      {
	type: "GET",
	url: "<?php echo url_for('catalogue/addValue');?>/num/" + (0+$('#catalogue_keywords tbody tr').length) + "/keyword/" + $(this).attr('alt') + "/value/" + tag_value,
	success: function(html)
	{
	  $('#catalogue_keywords tbody').append(html);
	}
      });
    }

  });

  $('#catalogue_keywords .clear_prop').live('click', clearPropertyValue);

});
</script>