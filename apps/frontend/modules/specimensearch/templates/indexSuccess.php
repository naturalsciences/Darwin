<?php slot('title', __('Search Specimens'));  ?>  

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimensearch')); ?>      
<div class="encoding">
 <?php include_partial('searchForm', array('form' => $form,'widgets' => $widgets, 'fields' => $fields)) ?>
</div>
