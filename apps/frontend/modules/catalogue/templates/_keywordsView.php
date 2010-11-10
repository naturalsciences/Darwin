<?php $form_kws = array();
$avail_kw = ClassificationKeywords::getTags();
foreach($form['ClassificationKeywords'] as $keyword)
{
  $type = $keyword['keyword_type']->getValue();
  if(!isset($form_kws[$type]))
    $form_kws[$type] = array();
  $form_kws[$type][] = $keyword;
}
foreach($form['newVal'] as $keyword)
{
  $type = $keyword['keyword_type']->getValue();
  if(!isset($form_kws[$type]))
    $form_kws[$type] = array();
  $form_kws[$type][] = $keyword;
}?>
<div id="catalogue_keywords<?php if(isset($view)) echo ('_view') ;?>">
      <table>
      <thead>
	<tr>
	  <th><?php echo __('Type');?></th>
	  <th><?php echo __('Keyword');?></th>
	</tr>
      </thead>
      <tbody>
	  <?php foreach($form_kws as $type => $keywords):?>
	    <tr>
	      <td><span><?php echo $avail_kw[$type];?></span></td>
	      <td><table alt="<?php echo $type;?>">
	      <?php foreach($keywords as $i => $keyword):?>
		  <tr>
		    <?php include_partial('catalogue/nameValue', array('form' => $keyword, 'show_name' => ($i ==0? true:false),'view' => isset($view)?true:false));?>
		  </tr>
	      <?php endforeach;?>
	      </table></td>
	    </tr>
	  <?php endforeach;?>
      </tbody>
      </table>
</div>


<script language="javascript">
$(document).ready(function () {

  $('.name_tags li').click(function()
  {
    var tag_value = returnText($('#<?php echo $field_name;?>'));
    var tag_key = $(this).attr('alt');
    var tag_key_name = $(this).text();
    if( trim(tag_value) != '')
    {
      $.ajax(
      {
	type: "GET",
	url: "<?php echo url_for('catalogue/addValue?table='.$table_name);?>/num/" + (0+$('#catalogue_keywords tbody tr').length) + "/keyword/" + tag_key + "/value/" + tag_value,
	success: function(html)
	{
	  if(!$('#catalogue_keywords table table[alt="'+tag_key+'"]').length)
	  {
	    element = '<tr><td><span>'+tag_key_name+'</span></td><td><table alt="'+tag_key+'"></table></td></tr>';
	    $('#catalogue_keywords > table > tbody').append(element)
	  }
	  $('#catalogue_keywords table table[alt="'+tag_key+'"]').append('<tr>' + html + '</tr>');
	  $('#catalogue_keywords table table[alt="'+tag_key+'"]').closest('tr').show();
	}
      });
      clearSelection($('#<?php echo $field_name;?>'));
    }
    else
    {
      message = $('<div class="warn_message"><?php echo __('You must select a part of the name to add a tag');?></a>').hide();
      $('.name_tags').before(message);
      $(message).fadeIn('slow').animate({opacity: 1.0}, 3000).fadeOut('slow', function() { $(this).remove();});
    }
  });

  $('#catalogue_keywords .clear_prop').live('click', function()
  {
    parent = $(this).closest('tr');
    $(parent).find('input').val('');
    $(parent).hide();
    if(! $(parent).closest('table[alt!=""]').find('tr:visible').length)
      $(parent).closest('table[alt!=""]').closest('tr').hide();
  });

});
</script>
