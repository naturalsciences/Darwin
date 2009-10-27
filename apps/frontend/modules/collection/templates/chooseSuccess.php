<div class="page">
    <h1><?php echo __('Choose a collection');?></h1>
<script language="javascript">
$(document).ready(function () {
    $('.col_name span').click(function () {
        $('#specimen_collection_ref').val( getIdInClasses($(this).parent().parent()) );
        $('#specimen_collection_ref_name').text($(this).text());
        $('#specimen_collection_ref_button .but_text').text('Change !');
        $('.qtip-button').click();
    });
});
</script>
    <?php include_partial('collectionTree', array('institutions' => $institutions,'is_choose' => true)) ?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png');?><a href="<?php echo url_for('collection/new') ?>"><?php echo __('New');?></a>
    </p>
</div>