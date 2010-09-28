<div class="header_menu">
  <div class="menu_top">
    <table class="table_menu">
      <tr>
        <td rowspan="2">&nbsp;</td>
        <td><h1 class="title">DaRWIN</h1></td>
      
      </tr>
      <tr>
        <td>
          <ul>
            <li>Nos Collections</li>
            <li><a href="<?php echo url_for('search/search');?>"><?php echo __("Search"); ?></a></li>
            <li>Take a tour</li>
            <li>Contacts</li>
            <li>Liens</li>
          </ul>
        </td>
      </tr>
    </table>    
  </div>
  <div class="blue_line">
      <div class="login_zone">
        <?php echo form_tag('register/index', array('class'=>'register_form'));?>
        <?php include_component('login','MenuLogin') ; ?> 
        </form>
      </div>    
  </div>
</div>
