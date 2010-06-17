var ref_element_id = null;
var ref_element_name = null;
var ref_level_id = '';
var ref_caller_id = '';
$(document).ready(function () {
 $("a.add_collector").click(function(){
    $(this).qtip({
        content: {
            title: { text : 'Choose a Collector', button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 620, max: 1000},
            border: {radius:3},
            title: { background: '#5BABBD', color:'white'}
        },
        api: {
            beforeShow: function()
            {
                alert("show !!!") ;
                // Fade in the modal "blanket" using the defined show speed
			 ref_element_id = null;
			 ref_element_name = null;
                addBlackScreen()
                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
            },
            beforeHide: function()
            {
                alert("Hide !!!") ;
                // Fade out the modal "blanket" using the defined hide speed
                $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
                alert("Hide bis !!!") ;
            },
	       onHide: function()
	       {
	          alert("Pouf !!!") ;
               $('.result_choose_collector').die('click') ;
	          $(this.elements.target).qtip("destroy");
	       }
         }
    });
    return false;
 });
});
function addCollectorValue(people_ref)
{
  parent = $(this).closest('table.collectors');
  parentId = $(parent).attr('id');
  $.ajax(
  {
    type: "GET",    
    url: $('a.hidden').attr('href')+ (0+$('#spec_ident_collectors tbody tr').length)+'/people_ref/'+people_ref + '/iorder_by/' + ($('table#'+parentId+' .spec_ident_collectors_data:visible').length+1),
    success: function(html)
    {
      $('#spec_ident_collectors_body').append(html);
    }
  });
  return false;
}

