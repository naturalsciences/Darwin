<script>
jQuery(function(){
    if($('input#specimen_individuals_accuracy_0:checked').length)
    {
        $('#specimen_individuals_specimen_individuals_count_max').parent().hide();
    }
    $("input[name=specimen_individuals\\[accuracy\\]]").click(function ()
    {
        if($('input#specimen_individuals_accuracy_0:checked').length)
        {
            $('#specimen_individuals_specimen_individuals_count_max').parent().hide();
        }
        else
        {
            $('#specimen_individuals_specimen_individuals_count_max').parent().show();
        }
        if($('#specimen_individuals_specimen_individuals_count_max').val() < $('#specimen_individuals_specimen_individuals_count_min').val())
            $('#specimen_individuals_specimen_individuals_count_max').val($('#specimen_individuals_specimen_individuals_count_min').val());
    });
});
</script>
<ul>
    <?php echo $form['accuracy']->renderRow() ?>
    <?php echo $form['specimen_individuals_count_min']->renderRow() ?>
    <?php echo $form['specimen_individuals_count_max']->renderRow() ?>
</ul>