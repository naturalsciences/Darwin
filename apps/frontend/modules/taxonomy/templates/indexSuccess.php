<div class="page">
<script language="javascript">
$(document).ready(function () {
    $('#choose_taxa_button').click(function () {
	ref_element_id = $(this).data('taxa_id');
        window.location = "<?php echo url_for('taxonomy/edit');?>/id/"+ref_element_id;
    });
});
</script>
    <h1><?php echo __('Taxon Search');?></h1>
    <?php include_partial('catalogue/chooseItem', array('searchForm' => $searchForm)) ?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png');?><a href="<?php echo url_for('taxonomy/new') ?>"><?php echo __('New');?></a>
    </p>
</div>