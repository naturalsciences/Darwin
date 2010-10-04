jQuery(function () 
{
  $(".search_form").submit(function ()
  {
    $(".tree").slideUp().html("");
    $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $('.search_form').serialize(),
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


/**********************************
 * Specimen Search related functions
 * 
 ************************************/

/***
 * Update the visible columns list in specimens_search
 */
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

function getColVisible()
{
  column_str = '';
  if($('.column_menu ul > li.check').length)
  {
    $('.column_menu ul > li.check').each(function (index)
    {
      if(column_str != '') column_str += '|';
      column_str += $(this).attr('id').substr(3);
    });
  }
  return column_str;
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
  $('table.spec_results tbody tr.sub_row td:first').attr('colspan', cpt);
  $('#specimen_search_filters_col_fields').val(getColVisible());
}

/***
 * Hide or show table column when a column is checked as visible
 */
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
