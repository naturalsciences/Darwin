<script type="text/javascript">
$(document).ready(function () {
    $('#add_spec_method').click(function()
    {
        $('#specimen_collecting_method').after('<input name="specimen[collecting_method]" id="specimen_collecting_method_input" type="text"/>');
        $('#specimen_collecting_method').data('name',$('#specimen_collecting_method').attr('name')).attr('name','').hide();
        $('#add_spec_method').hide();
        $('#change_spec_method').show();
    });

    $('#change_spec_method').click(function()
    {
        $('#specimen_collecting_method_input').remove();
        $('#specimen_collecting_method').attr('name',$('#specimen_collecting_method').data('name')).show();
        $('#add_spec_method').show();
        $('#change_spec_method').hide();
    });
});
</script>
        <?php echo $form['collecting_method']->renderRow() ?> 
        <div id="add_spec_method"><?php echo image_tag('add_green.png', array('id'=> 'specimen_method_add', 'alt'=>'+'));?> Add Another method</div>
        <div id="change_spec_tool" class="hidden"><?php echo image_tag('refresh_green.png');?> Pick a Method in the List</div>