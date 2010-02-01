<?php slot('title', __('Search People'));  ?>        
<div class="page">
<h1>People Search</h1>

<script language="javascript">
$(document).ready(function () {
    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	ref_element_id = getIdInClasses(el);
	ref_element_name = el.find('td.item_name').text();
	$('.result_choose').die('click');
        $('.qtip-button').click();
    });
});
</script>

  <?php include_partial('searchForm', array('form' => $form, 'is_choose' => true)) ?>

</div>