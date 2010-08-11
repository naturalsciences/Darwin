jQuery(function () 
{
  /*$(".search_form").submit(function ()
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
  });*/

  $("a.sort").live('click',function ()
  {
    $.ajax({
            type: "POST",
            url: $(this).attr("href"),
            data: $('.search_form').serialize(),
            success: function(html){
                                    $(".search_results_content").html(html);
                                    $('.search_results').slideDown();
                                   }
           }
          );
    $(".search_results_content").html('<img src="/images/loader.gif" />');
    $(".search_form").attr('action', $(this).attr("href"))
    return false;
  });

  $(".pager a").live('click',function ()
  {
    $.ajax({
            type: "POST",
            url: $(this).attr("href"),
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
