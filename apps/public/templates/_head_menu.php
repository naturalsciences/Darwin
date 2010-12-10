<td class="header_menu">
  <div class="menu_top">
    <table>
      <tr>
        <td rowspan="2" style="height:90px">&nbsp;</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2">
          <ul class="menu_link">
            <li><?php echo link_to(__('Our Collections'),'@homepage');?></li>
            <li><?php echo link_to(__('Search'),'search/search');?></li>
            <li><?php echo link_to(__('Take a tour'),'@tour');?></li>
            <li><?php echo link_to(__('Contacts'),'@contact');?></li>
            <li><?php echo link_to(__('About'),'@about');?></li>
          </ul>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="lang_picker"><ul style="">
          <li><?php echo link_to('En','board/lang?lang=en');?></li>
          <li class="sep">|<li>
          <li><?php echo link_to('Fr','board/lang?lang=fr');?></li>
          <li class="sep">|<li>
          <li><?php echo link_to('Nl','board/lang?lang=nl');?></li>
        </ul></td>
      </tr>
    </table>    
  </div>

  <?php include_component('login','MenuLogin') ; ?> 

</td>