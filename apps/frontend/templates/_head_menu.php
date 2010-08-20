<div class="menu_top">
    <ul class="sf-menu main_menu">
        <li class="house"><?php echo link_to(image_tag('home.png', 'alt=Home'),'board/index');?></li>
        <li>
            <a href="#"><?php echo __('My Preferences');?></a>
            <ul>
              <li><?php echo link_to(__('My Profile'),'user/profile');?></li>
              <li><?php echo link_to(__('My Widgets'),'user/widget');?></li>
            </ul>
        </li>
        <li>
            <a href="#"><?php echo __('Searches');?></a>
            <ul>
                <!--<li>
                    <a href="#aa">My Searches</a>
                    <ul>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                    </ul>
                </li>-->
                <li>
                    <a href="#"><?php echo __('Catalogues');?></a>
                    <ul>
                        <li><?php echo link_to(__('Taxonomy'),'taxonomy/index');?></li>
                        <li><?php echo link_to(__('Chronostratigraphy'),'chronostratigraphy/index');?></li>
                        <li><?php echo link_to(__('Lithostratigraphy'),'lithostratigraphy/index');?></li>
                        <li><?php echo link_to(__('Lithology'),'lithology/index');?></li>
                        <li><?php echo link_to(__('Mineralogy'),'mineralogy/index');?></li>
                        <li><?php echo link_to(__('Expeditions'),'expedition/index');?></li>
                        <li><?php echo link_to(__('I.G. Numbers'),'igs/index');?></li>
                        <li><?php echo link_to(__('Institutions'),'institution/index');?></li>
                        <li><?php echo link_to(__('People'),'people/index');?></li>
                        <li><?php echo link_to(__('Sampling location'),'gtu/index');?></li>
                        <li><?php echo link_to(__('Collecting Methods'),'methods_and_tools/methodsIndex');?></li>
                        <li><?php echo link_to(__('Collecting Tools'),'methods_and_tools/toolsIndex');?></li>
                    </ul>
                </li>
                <li><?php echo link_to(__('Specimens'),'specimensearch/index');?></li>
                <li><?php echo link_to(__('Pinned Specimens'),'specimensearch/search?pinned=true');?></li>
                <li><?php echo link_to(__('Collections'),'collection/index');?></li>
            </ul>
        </li>
        <?php if($sf_user->getDbUserType() >= Users::ENCODER) : ?>
        <li>
            <a href="#"><?php echo __('Add');?></a>
            <ul>
                <li>
                    <a href="#"><?php echo __('Catalogues');?></a>
                    <ul>
                        <li><?php echo link_to(__('Taxonomy'),'taxonomy/new');?></li>
                        <li><?php echo link_to(__('Chronostratigraphy'),'chronostratigraphy/new');?></li>
                        <li><?php echo link_to(__('Lithostratigraphy'),'lithostratigraphy/new');?></li>
                        <li><?php echo link_to(__('Lithology'),'lithology/new');?></li>
                        <li><?php echo link_to(__('Mineralogy'),'mineralogy/new');?></li>
                        <li><?php echo link_to(__('Expeditions'),'expedition/new');?></li>
                        <li><?php echo link_to(__('RBINS I.G. Numbers'),'igs/new');?></li>
                        <li><?php echo link_to(__('Institutions'),'institution/new');?></li>
                        <li><?php echo link_to(__('People'),'people/new');?></li>
                        <li><?php echo link_to(__('Sampling location'),'gtu/new');?></li>
                    </ul>
                </li>
                <li><?php echo link_to(__('Specimens'),'specimen/new');?></li>
                <?php if($sf_user->getDbUserType() >= Users::MANAGER) : ?>
                <li><?php echo link_to(__('Collections'),'collection/new');?></li>
                <?php endif ?>
            </ul>
        </li>
        <?php endif ?>
        <?php if($sf_user->getDbUserType() >= Users::MANAGER) : ?>
        <li>
            <a href=""><?php echo __('Administration');?></a>
            <ul>
                <?php if($sf_user->getDbUserType() >= Users::ADMIN) : ?>
                <li><?php echo link_to('Reload DB','account/reload','confirm=Are you sure?');?></li>                
                <li><?php echo link_to('Big Brother','bigbro/index');?></li>
                <?php endif ?>
                <li>
                	<a href="#"><?php echo __('User');?></a>
                	<ul>
                		<li><?php echo link_to(__('Add'),'user/new');?></li>
                		<li><?php echo link_to(__('Search'),'user/index');?></li>
                	</ul>
                </li>
            </ul>
        </li>
		<li>
            <a href=""><?php echo __('Help');?></a>
            <ul>
                <li><?php echo link_to('Help','help/index');?></li>                
                <li><?php echo link_to('Contacts','help/contact');?></li>
                <li><?php echo link_to('Contribute','help/contrib');?></li>
                <li><?php echo link_to('About','help/about');?></li>
            </ul>
        </li>
        <?php endif ?>
        <li class="exit" ><?php echo link_to(image_tag('exit.png', 'alt=Exit'),'account/logout');?></li>
    </ul>
</div>
<script  type="text/javascript">

$(document).ready(function () {
  o = {"dropShadows":false, "autoArrows":true, "firstOnClick":true, "delay":400};
  $('ul.main_menu').supersubs().superfish(o);
  $('ul.main_menu > li:not(.house):not(.exit)').append('<img class="highlight" src="/images/menu_expand.png" alt="" />');
});
</script>
