<?php slot('widget_title',__('Collecting Tool'));  ?>
<script type="text/javascript">
$(document).ready(function () {
    $('#specimen_tool_add').click(function()
    {
        $('#specimen_collecting_tool').after('<input name="specimen[collecting_tool]" id="specimen_collecting_tool_input" type="text"/>');
        $('#specimen_collecting_tool').remove();
    });
});
</script>
<?php echo $form['collecting_tool']->renderRow() ?> 
<?php echo image_tag('add_green.png', array('id'=> 'specimen_tool_add', 'alt'=>'+'));?> Add Another Tool