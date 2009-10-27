<script src="http://dev.jquery.com/view/trunk/plugins/ajaxQueue/jquery.ajaxQueue.js"></script>
<script>
var cnt = 0;
$(document).ready(function(){
    
    $("#people_name").bind('paste input', function(event)
    {
      $.ajax({
      mode: 'abort', 
      type: "GET",
      url: "<?php echo url_for('people/complete');?>",
      data: ( {name : $(this).val()} ),
      beforeSend: function(){
	// Handle the beforeSend event
      },
      complete: function(){
	// Handle the beforeSend event
      },
      success: function(result){
	$(".results ul").html(result);
      },
    });
  });
});
</script>
<!--<form action="#" name="people_search">-->
  <input type="text" name="people_name" id="people_name" value="" />
<!--</form> -->
<div class="results">
  <ul>
  
  </ul>
</div>