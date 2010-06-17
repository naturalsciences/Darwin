<?php slot('title', __('Search People'));  ?>        
<div class="page">
<h1><?php echo __('Search People');?></h1>

<?php if($sf_params->get('with_js',true) == true):?>
<script language="javascript">
$(document).ready(function () {
    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	ref_element_id = getIdInClasses(el);
	ref_element_name = el.find('td.item_name').text();
	$('.result_choose').die('click');
        $('.qtip-button').click();
    });
    $('.result_choose_collector').live('click',function () {
	el = $(this).closest('tr');
	ref_element_id = getIdInClasses(el);
	$info = 'good' ;
	$('#spec_ident_collectors tbody tr').each(function() {
	    if($(this).find('input[id$=\"_people_ref\"]').val() == ref_element_id) $info = 'bad' ;
	});
	if($info == 'good') addCollRightValue(ref_element_id);
    });
});
</script>
<?php endif;?>
  <?php include_partial('searchForm', array('form' => $form, 'is_choose' => true)) ?>

</div>
