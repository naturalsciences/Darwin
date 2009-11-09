<div class="page">
    <h1><?php echo __('Choose a collection');?></h1>
<script language="javascript">
$(document).ready(function () {
    $('.col_name span').click(function () {
	ref_element_id = getIdInClasses($(this).parent().parent());
	ref_element_name = $(this).text();
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