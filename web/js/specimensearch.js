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
* Notify the application about the visible column in the search
*/
function store_list(element, url)
{
  query_str = '';
  element.find('>li').each(function(index) {
    if( $(this).hasClass('check'))
    {
      if(query_str !='')
        query_str += '|';
      
      query_str += $(this).attr('id').substr(3); // extract from id the column name : li_colname ==> colname
    }
  });
  $('#specimen_search_filters_col_fields').attr('value',query_str) ;
  $.ajax({
    url: url + '/cols/'+query_str,
    success: function(data) {
    }
  });
  $('#specimen_search_filters_fields').val(query_str);
}

/**
* set the individual colspan depending on how many fields are visible
*/
function initIndividualColspan()
{
  cpt = 1 ;
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
  colspan = $('table.spec_results tbody tr:nth-child(2) td').attr('colspan') ;
  if(val == 'uncheck')
  {
    $("li #"+field).find('span:first').hide();
    $("li #"+field).find('span:nth-child(2)').show();
    $('table.spec_results thead tr th.col_'+column).hide();
    $('table.spec_results tbody tr td.col_'+column).hide();
    //this line below is neccessary to avoid table border to be cut
    $('table.spec_results tbody tr:nth-child(2) td').attr('colspan',colspan-1) ;
  }
  else
  {
    $("li #"+field).find('span:first').show();
    $("li #"+field).find('span:nth-child(2)').hide();
    $('table.spec_results thead tr th.col_'+column).show();
    $('table.spec_results tbody tr td.col_'+column).show();
    //this line below is neccessary to avoid table border to be cut    
    $('table.spec_results tbody tr:nth-child(2) td').attr('colspan',colspan+1) ;    
  }
}
