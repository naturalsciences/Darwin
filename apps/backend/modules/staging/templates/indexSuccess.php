<?php slot('title',__('Display of import file'));?>
<div class="page staging_filter">
  <?php echo form_tag('staging/search?import='.$import->getId(), array('class'=>'search_form','id'=>'import_filter'));?>
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>

  <div class="container">
    <?php echo $form['only_errors']->renderRow();?><input type="submit" value="<?php echo __('Search');?>"/>
    <div class="blue_link"><?php echo link_to(__('Back to Import'), 'import/index');?></div>
    <div class="search_results">
      <div class="search_results_content">

      </div>
    </div>
  </div>
  </form>

</div>
<script language="javascript">
$(document).ready(function () {
  $('.staging_filter').choose_form({});
  $('#import_filter').submit();
});
</script>