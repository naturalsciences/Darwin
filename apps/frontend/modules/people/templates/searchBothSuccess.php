<h2><?php echo __('Search');?> :</h2>
  <?php echo __('People');?> : <input name="type_search" type="radio" value="people" />
  <?php echo __('Institution');?> <input name="type_search" type="radio" value="institution" />

<?php $is_choose=true;?>

<script language="javascript">

  $(document).ready(function () {
    $('input[name="type_search"]').attr("checked", false); 
    $('input[name="type_search"]').change(function()
    {
      $(".search_both_item").html('Searching');
      ;
      people_search_url = '<?php echo url_for('people/choose' . ($is_choose?'?is_choose=1' : '') );?>';
      institution_search_url = '<?php echo url_for('institution/choose' . ($is_choose?'?is_choose=1' : '') );?>';

      if( $('input[name="type_search"]:checked').val() == 'people' )
	search_url = people_search_url;
      else
	search_url = institution_search_url

      $.ajax({
	  type: "POST",
	  url: search_url,
	  success: function(html){
	    $('.search_both_item').html(html);
	  }});
	  return false;
      });
  });

</script> 
<div class="search_both_item">
  
</div>