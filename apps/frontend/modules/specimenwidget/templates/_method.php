<?php slot('widget_title',__('Collecting Method'));  ?>
<script type="text/javascript">
$(document).ready(function () {
    $('#specimen_method_add').click(function()
    {
        $('#specimen_collecting_method').after('<input name="specimen[collecting_method]" id="specimen_collecting_method_input" type="text"/>');
        $('#specimen_collecting_method').remove();
    });
});
</script>
        <?php echo $form['collecting_method']->renderRow() ?> 
        <?php echo image_tag('add_green.png', array('id'=> 'specimen_method_add', 'alt'=>'+'));?> Add Another Method