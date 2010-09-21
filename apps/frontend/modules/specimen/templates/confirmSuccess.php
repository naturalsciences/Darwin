<div class="edition">
  <h2><?php echo __('Do you want to also duplicate hidden data');?></h2>
  <script language="javascript">
    $(document).ready(function () {
      $('.check_right > input[type=submit]').click(function () {
          element_name = '';
          if ($(this).attr('name') == 'yes')
            element_name = '/all_duplicate/1';
          //$('.edition input[type=button]').unbind('click');
          $('.qtip-button').click();
      });
    });
  </script>
  <p class="check_right">
    <input type="submit" value="<?php echo __('Yes'); ?>" name="yes">&nbsp;
    <input type="submit" value="<?php echo __('No'); ?>" name="no">
  </p>
</div>
