<div class="page">
    <h1><?php echo __('Choose a Taxon');?></h1>
<script language="javascript">
$(document).ready(function () {
    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	ref_element_id = getIdInClasses(el);
	ref_element_name = el.find('span.item_name').text();
	$('.result_choose').die('click');
        $('.qtip-button').click();
    });
});
</script>
    <?php include_partial('catalogue/chooseItem', array('searchForm' => $searchForm,'is_choose' => true)) ?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png');?><a href="<?php echo url_for('taxonomy/new') ?>"><?php echo __('New');?></a>
    </p>
</div>