<?php slot('title', __('Search User'));  ?>        
<div class="page">
<h1><?php echo __('Search User');?></h1>

<script language="javascript">
$(document).ready(function () {
 
/*  $('.result_choose').live('click',function () {
	  el = $(this).closest('tr');
	  ref_element_id = getIdInClasses(el);
	  ref_element_name = el.find('td.item_name').text();
	  $('.result_choose').die('click');
    $('.qtip-button').click();
  });*/
  $('.result_choose').live('click',function () {
    if(typeof referer != 'undefined')
    {
	    el = $(this).closest('tr');
	    ref_element_id = getIdInClasses(el);
	    $info = 'good' ;
	    $('table#'+referer+' tbody tr').each(function() {
	      if($(this).attr('id') == ref_element_id) $info = 'bad' ;
	    });
	    if($info == 'good') addCollRightValue(ref_element_id,referer);
	  }
	  else
	  {
	    el = $(this).closest('tr');
	    ref_element_id = getIdInClasses(el);
	    ref_element_name = el.find('td.item_name').text();
	    $('.result_choose').die('click');
      $('.qtip-button').click();	  
	  }
  });
});
</script>

  <?php include_partial('searchForm', array('form' => $form, 'is_choose' => true)) ?>

</div>
