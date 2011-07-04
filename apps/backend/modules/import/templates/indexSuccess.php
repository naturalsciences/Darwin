<?php slot('title', __('Import files summary'));  ?>        

<div class="page">
<h1><?php echo __('Imports');?> :</h1>

    <?php include_partial('searchForm', array('form' => $form)) ?>
</div>

<script language="javascript">
$(document).ready(function () {
  $('#import_filter').submit();
  $('#imports_filters_state').change(function()
  {
    if(/^to_be_loaded|loading|loaded|checking|pending|processing$/.test($(this).val()))
      $('#imports_filters_show_finished').removeAttr('checked');
    else
      $('#imports_filters_show_finished').attr('checked','checked');
  });
});
</script>
