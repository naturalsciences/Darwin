<?php slot('title', __('Dashboard'));  ?>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'board')) ?>
<div class="board">

 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'encod' => false,
	'category' => 'boardwidget',
	'columns' => 2,
	'options' => array()
	)); ?>
</div>
<input type="hidden" id="refreshed" value="no">
<script type="text/javascript">
onload=function(){
var e=document.getElementById("refreshed");
if(e.value=="no")e.value="yes";
else{e.value="no";location.reload();}
}
</script>