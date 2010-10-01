<div class="header_menu">
  <div class="menu_top">
    <table>
      <tr>
        <td rowspan="2" style="height:100px">&nbsp;</td>
        <td></td>
      </tr>
      <tr>
        <td>
          <ul class="menu_link">
            <li><a href="#">OUR COLLECTIONS</a></li>
            <li><a href="<?php echo url_for('search/search');?>"><?php echo __("SEARCH"); ?></a></li>
            <li><a href="#">TAKE A TOUR</a></li>
            <li><a href="#">CONTACTS</a></li>
            <li><a href="#">LINKS</a></li>
          </ul>
        </td>
      </tr>
    </table>    
  </div>
  <div class="blue_line">
    <table>
      <tr>
        <td>&nbsp;</td>
        <td class="blue_line_right">
          <?php echo form_tag('register/login', array('class'=>'register_form'));?>
          <?php include_component('login','MenuLogin') ; ?> 
          </form>
        </td>
      </tr>
    </table>    
  </div>
</div>
