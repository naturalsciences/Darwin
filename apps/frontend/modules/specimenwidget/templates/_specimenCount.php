<script>
jQuery(function(){
    if($('input#specimen_accuracy_0:checked').length)
    {
        $('#specimen_specimen_count_max').parent().hide();
    }
    $("input[name=specimen\\[accuracy\\]]").click(function ()
    {
        if($('input#specimen_accuracy_0:checked').length)
        {
            $('#specimen_specimen_count_max').parent().hide();
        }
        else
        {
            $('#specimen_specimen_count_max').parent().show();
        }
        if($('#specimen_specimen_count_max').val() < $('#specimen_specimen_count_min').val())
            $('#specimen_specimen_count_max').val($('#specimen_specimen_count_min').val());
    });
});
</script>
<ul>
    <?php echo $form['accuracy']->renderRow() ?>
    <?php echo $form['specimen_count_min']->renderRow() ?>
    <?php echo $form['specimen_count_max']->renderRow() ?>
</ul>