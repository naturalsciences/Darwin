<script language="javascript">
$(document).ready(function () {
  $('.result_choose').live('click',function () {
    el = $(this).closest('tr');
    ref_element_id = getIdInClasses(el);
    ref_element_name = el.find('td.item_name').text();
    ref_code_name = el.find('td.code_name > ul > li:first').text();
    ref_element_name = ref_code_name+" - "+ ref_element_name ;
    if(typeof fct_update=="function")
    {
      fct_update(ref_element_id, ref_element_name);
    }
    else
    {
      $('.result_choose').die('click');
      $('body').trigger('close_modal')
    }
  });
});
</script>
<?php include_partial('overviewTable', array('parts' => $parts, 'codes' => $codes, 'individual'=> $individual, 'is_choose'=>true, 'view' => true)); ?>
