<td class="header_menu">
  <div class="menu_top">
    <table>
      <tr>
        <td rowspan="2" style="height:100px">&nbsp;</td>
        <td></td>
      </tr>
      <tr>
        <td>
          <ul class="menu_link">
            <li><a href="#">Our collections</a></li>
            <li><a href="<?php echo url_for('search/search');?>"><?php echo __("Search"); ?></a></li>
            <li><a href="#">Take a tour</a></li>
            <li><a href="#">Contacts</a></li>
            <li><a href="#">Links</a></li>
          </ul>
        </td>
      </tr>
    </table>    
  </div>

  <?php include_component('login','MenuLogin') ; ?> 

</td>
