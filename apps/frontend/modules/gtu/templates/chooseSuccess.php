<?php slot('title', __('Search sampling location'));  ?>
<div class="page">
<h1><?php echo __('Sampling location search');?></h1>

<?php if($sf_params->get('with_js',true) == true):?>

<script language="javascript">
$(document).ready(function () {
    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	ref_element_id = getIdInClasses(el);
	ref_element_name = el.find('td.item_name').html();
	$('.result_choose').die('click');
        $('.qtip-button').click();
    });
});
</script>
<?php endif;?>
  <?php include_partial('searchForm', array('form' => $form, 'is_choose' => true)) ?>

</div>
