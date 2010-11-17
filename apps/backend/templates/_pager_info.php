<?php if(isset($pagerLayout) && isset($form['rec_per_page'])): ?>
    <div class="pager paging_info">
      <table>
        <tr>
          <td><?php echo image_tag('info2.png');?></td>
          <td>
	    <?php echo format_number_choice('[0]No Results Retrieved|[1]Your query retrieved 1 record|(1,+Inf]Your query retrieved %1% records', array('%1%' =>  $pagerLayout->getPager()->getNumResults()),  $pagerLayout->getPager()->getNumResults()) ?>
	  </td>
          <td><ul><li><?php echo $form['rec_per_page']->renderLabel(); echo $form['rec_per_page']->render(); ?></li></ul></td>
        </tr>
      </table>
    </div>

  <script type="text/javascript">
  $(document).ready(function () {
    $(".rec_per_page").change(function (event)
    {
      event.preventDefault();
      $.ajax({
        type: "POST",
        url: $(this).closest('form').attr('action'),
        data: $(this).closest('form').serialize(),
        success: function(html) {
          $(".search_results_content").html(html);
          $('.search_results').slideDown();
        }
      });
      
      $(".search_results_content").html('<img src="/images/loader.gif" />');
    });

    $("a.sort").click(function (event)
    {
      event.preventDefault();
      $.ajax({
        type: "POST",
        url: $(this).attr("href"),
        data: $(this).closest('form').serialize(),
        success: function(html){
          $(".search_results_content").html(html);
          $('.search_results').slideDown();
        }
      });
      $(".search_results_content").html('<img src="/images/loader.gif" />');
      $(this).closest('form').attr('action', $(this).attr("href"))
    });
  
    $(".pager a").click(function (event)
    {
      event.preventDefault();
      $.ajax({
        type: "POST",
        url: $(this).attr("href"),
        data: $(this).closest('form').serialize(),
        success: function(html){
          $(".search_results_content").html(html);
          $('.search_results').slideDown();
        }
      });
      $(".search_results_content").html('<img src="/images/loader.gif" />');
    });

  });
  </script>
<?php endif; ?>
