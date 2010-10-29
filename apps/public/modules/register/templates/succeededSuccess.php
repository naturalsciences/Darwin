<div class="page well_registered">
  <div id="login_arrow">
    <div id="login_arrow_top_blue">
      <?php echo image_tag('/images/collapse_big.png', array('alt'=>'Login above'));?>
    </div>
    <div id="login_arrow_top_white">
      <?php echo image_tag('/images/collapse_white.png', array('alt'=>'Login above'));?>
    </div>
    <div id="login_arrow_middle_blue">
      <?php echo image_tag('/images/collapse.png', array('alt'=>'Login above'));?>
    </div>
    <div id="login_arrow_middle_white">
      <?php echo image_tag('/images/collapse_white.png', array('alt'=>'Login above'));?>
    </div>
  </div>
  <div class="white_content">
    <?php if(isset($params['name']) && isset($params['physical']) && isset($params['title'])):?>
      <?php if($params['physical'] && !empty($params['name'])):?>
        <p><?php echo __('Dear').' '.((empty($params['title']))?'':$params['title'].' ').$params['name'].',';?></p>
      <?php endif;?>
    <?php endif;?>
    <p><?php echo __('Thank you for having registered on DaRWIN 2.');?></p>
    <p><?php echo __('You can now log you in and enjoy enhanced services to our collections.');?></p>
    <p id="team_signature"><?php echo __('DaRWIN 2  team.');?></p>
  </div>
</div>
