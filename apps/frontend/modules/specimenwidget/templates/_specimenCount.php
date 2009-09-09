<?php slot('widget_title',__('Specimen Count'));  ?>
<script>
jQuery(function(){
    if($('input#specimen_accuracy_0:checked').length)
    {
        $('#specimen_specimen_count_max').parent().hide();
    }
});
</script>
<ul>
    <?php echo $form['accuracy']->renderRow() ?>
    <?php echo $form['specimen_count_min']->renderRow() ?>
    <?php echo $form['specimen_count_max']->renderRow() ?>
</ul>