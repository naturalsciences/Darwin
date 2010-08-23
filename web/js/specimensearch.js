jQuery(function() {
  $("a.sort").live('click',function ()
  {
    $.ajax({
            type: "POST",
            url: $(this).attr("href"),
            data: $('.specimensearch_form').serialize(),
            success: function(html){
                                    $(".search_results_content").html(html);
                                    $('.search_results').slideDown();
                                   }
           }
          );
    $(".search_results_content").html('<img src="/images/loader.gif" />');
    $(".specimensearch_form").attr('action', $(this).attr("href"))
    return false;
  });

  $(".pager a").live('click',function ()
  {
    $.ajax({
            type: "POST",
            url: $(this).attr("href"),
            data: $('.specimensearch_form').serialize(),
            success: function(html){
                                    $(".search_results_content").html(html);
                                    $('.search_results').slideDown();
                                   }
           }
          );
    $(".search_results_content").html('<img src="/images/loader.gif" />');
    return false;
  });
});

function update_list(li)
{
  val = li.attr('class') ;
  if (val == 'check')
  {
    li.removeClass('check') ;
    li.addClass('uncheck') ; 
  }
  else
  {
    li.removeClass('uncheck') ;
    li.addClass('check') ; 
  }
}

/**
* set the individual colspan depending on how many fields are visible
*/
function initIndividualColspan()
{
  cpt = 2 ;
  $('ul.column_menu > li > ul').find('>li').each(function() {
    if( $(this).hasClass('check'))
    {
      cpt++ ;
    }
   });  
  $('table.spec_results tbody tr:nth-child(2) td').attr('colspan', cpt) ;    
}

function hide_or_show(li)
{
  field = li.attr('id') ;
  column = field.substr(3) ;
  val = li.attr('class') ;
  if(val == 'uncheck')
  {
    $("li #"+field).find('span:first').hide();
    $("li #"+field).find('span:nth-child(2)').show();
    $('table.spec_results thead tr th.col_'+column).hide();
    $('table.spec_results tbody tr td.col_'+column).hide();
    //this line below is neccessary to avoid table border to be cut
  }
  else
  {
    $("li #"+field).find('span:first').show();
    $("li #"+field).find('span:nth-child(2)').hide();
    $('table.spec_results thead tr th.col_'+column).show();
    $('table.spec_results tbody tr td.col_'+column).show();
    //this line below is neccessary to avoid table border to be cut    
  }
  initIndividualColspan();

}
