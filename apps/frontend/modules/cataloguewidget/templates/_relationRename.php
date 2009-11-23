<?php slot('widget_title',__('Renamed'));  ?>
<table>
  <?php foreach($relations as $renamed):?>
  <tr>
    <th>
    Renamed to
    </th>
    <td>
      <a class="link_catalogue" href="<?php echo url_for('catalogue/rename?table=taxonomy&id='.$eid.'&relid='.$renamed[0]) ?>"><?php echo $renamed[5]//Rec Name?></a>
    <td>
  </tr>
  <?php endforeach;?>
</table>
  <?php if(count($relations) != -1):?>
    <br />
    <?php echo image_tag('add_green.png');?><a class="link_catalogue" href="<?php echo url_for('catalogue/rename?table=taxonomy&id='.$eid) ?>"><?php echo __('Add');?></a>
  <?php endif;?>

<script>
  $("a.link_catalogue").live('click', function(){
    $(this).qtip({
        content: {
            title: { text : $(this).text(), button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 500, max: 1000}
        },
        api: {
            beforeShow: function()
            {
                // Fade in the modal "blanket" using the defined show speed
		ref_element_id = null;
		ref_element_name = null;
                addBlackScreen()
                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
            },
            beforeHide: function()
            {
                // Fade out the modal "blanket" using the defined hide speed
                $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
            },
	    onHide: function()
	    {	
		if(ref_element_id != null && ref_element_name != null)
		{
		  parent_el = $(this.elements.target).parent().prevAll('.ref_name');
		  parent_el.text(ref_element_name);
		  parent_el.prev().val(ref_element_id);
		  $(this.elements.target).parent().prevAll('.ref_clear').show();
		  $(this.elements.target).text('Change !');
		}
		$(this.elements.target).qtip("destroy");
	    }
        }
    });
    return false;
  });
</script>