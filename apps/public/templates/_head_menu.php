<div class="menu_top">
    <ul class="sf-menu main_menu">
        <li class="house"><?php echo link_to(image_tag('home.png', 'alt=Home'),'board/index');?></li>
        <li>
            <?php echo link_to(__('Search'),'search/index');?>            
        </li>
        <li class="exit" ><?php echo link_to(image_tag('exit.png', 'alt=Exit'),'account/logout');?></li>
    </ul>
</div>
<script  type="text/javascript">

$(document).ready(function () {
  o = {"dropShadows":false, "autoArrows":true,"delay":400};
  $('ul.main_menu').supersubs().superfish(o);
  $('ul.main_menu > li:not(.house):not(.exit)').append('<img class="highlight" src="/images/menu_expand.png" alt="" />');
});
</script>
