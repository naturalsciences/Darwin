<?php slot('widget_title',__('Collecting Tool'));  ?>
<script type="text/javascript">
$(document).ready(function () {
    $('#add_spec_tool').click(function()
    {
        $('#specimen_collecting_tool').after('<input name="specimen[collecting_tool]" id="specimen_collecting_tool_input" type="text"/>');
        $('#specimen_collecting_tool').data('name',$('#specimen_collecting_tool').attr('name')).attr('name','').hide();
        $('#add_spec_tool').hide();
        $('#change_spec_tool').show();
    });

    $('#change_spec_tool').click(function()
    {
        $('#specimen_collecting_tool_input').remove();
        $('#specimen_collecting_tool').attr('name',$('#specimen_collecting_tool').data('name')).show();
        $('#add_spec_tool').show();
        $('#change_spec_tool').hide();
    });
});
</script>
<?php echo $form['collecting_tool']->renderRow() ?> 
<div id="add_spec_tool"><?php echo image_tag('add_green.png', array('id'=> 'specimen_tool_add', 'alt'=>'+'));?> Add Another Tool</div>
<div id="change_spec_tool" class="hidden"><?php echo image_tag('refresh_green.png');?> Pick a Tool in the List</div>