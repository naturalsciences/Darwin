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
    if(typeof fct_update=="function")
    {
      fct_update(ref_element_id, ref_element_name);
    }
    else
    {
      $('.result_choose').die('click');
        $('.qtip-button').click();
    }
  });
/*
  if(typeof only_role=='undefined')
    only_role=0;
  /*if(only_role == 16)
  {
    $('.result_choose_collector').live('click',function () {
      el = $(this).closest('tr');
      ref_element_id = getIdInClasses(el);
      info = 'good' ;
      $('table#spec_ident_collectors tbody tr').each(function() {
	      if($(this).find('input[id$=\"_people_ref\"]').val() == ref_element_id) info = 'bad' ;
      });
      if(info == 'good') addCollectorValue(ref_element_id);
    });
   }*//*
   if (only_role == 4)
   {
    $('.result_choose_identifier').live('click',function () {
      el = $(this).closest('tr');
      ref_element_id = getIdInClasses(el);
      info = 'good' ;
      $(ref_table+' tbody.spec_ident_identifiers_data').each(function() {
	      if($(this).find('input[id$=\"_people_ref\"]').val() == ref_element_id) info = 'bad' ;
      });
      if(info == 'good') addIdentifierValue(ref_element_id,ref_table);
    });
   }*/
});
</script>
<?php endif;?>
  <?php include_partial('searchForm', array('form' => $form, 'is_choose' => true)) ?>

</div>
