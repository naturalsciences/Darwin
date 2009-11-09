<div class="page">
    <h1><?php echo __('Choose a Taxon');?></h1>
<script language="javascript">
$(document).ready(function () {
    $('#choose_taxa_button').click(function () {
	ref_element_id = $(this).data('taxa_id');
	ref_element_name = $(this).data('taxa_name');
        $('.qtip-button').click();
    });
});
</script>
    <?php include_partial('taxTree', array('searchForm' => $searchForm,'is_choose' => true)) ?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png');?><a href="<?php echo url_for('taxonomy/new') ?>"><?php echo __('New');?></a>
    </p>
</div>