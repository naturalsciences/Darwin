<?php slot('title', __('Import files summary'));  ?>        

<div class="page">
<h1><?php echo __('Imports');?> :</h1>

    <?php include_partial('searchForm', array('form' => $form)) ?>
</div>

<script language="javascript">
$(document).ready(function () {
  $('#import_filter').submit();
});
</script>
