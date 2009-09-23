<div class="page">
    <h1>Choose a collection</h1>
<script language="javascript">
$(document).ready(function () {
    $('.col_name span').click(function () {
        $('#collection_ref_name').text($(this).text());
        $('#specimen_collection_ref').val( getIdInClasses($(this).parent().parent()) );
        $('.qtip-button').click();
    });
});
</script>
    <?php include_partial('collectionTree', array('institutions' => $institutions,'is_choose' => true)) ?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png');?><a href="<?php echo url_for('collection/new') ?>">New</a>
    </p>
</div>