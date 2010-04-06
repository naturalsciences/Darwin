<div class="menu_top">
    <ul class="sf-menu main_menu">
        <li class="house"><?php echo link_to(image_tag('home.png', 'alt=Home'),'board/index');?></li>
        <li>
            <?php echo link_to(__('My Profile'),'user/profile');?>
            <ul>
                <li><a href="#aa">My data</a></li>
                <li><a href="#aa">Preferences</a></li>
                <li><a href="#aa">Rights</a></li>
            </ul>
        </li>
        <li>
            <a href="#"><?php echo __('Searches');?></a>
            <ul>
                <li>
                    <a href="#aa">My Searches</a>
                    <ul>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                        <li><a href="#">menu item</a></li>
                    </ul>
                </li>
                <li><a href="./?page=result">Specimens</a></li>
                <li>
                    <a href="">Catalogues</a>
                    <ul>
                        <li><?php echo link_to(__('Taxonomy'),'taxonomy/index');?></li>
                        <li><?php echo link_to(__('Chronostratigraphy'),'chronostratigraphy/index');?></li>
                        <li><?php echo link_to(__('Lithostratigraphy'),'lithostratigraphy/index');?></li>
                        <li><?php echo link_to(__('Lithology'),'lithology/index');?></li>
                        <li><?php echo link_to(__('Mineralogy'),'mineralogy/index');?></li>
                        <li><?php echo link_to(__('Collections'),'collection/index');?></li>
                        <li><?php echo link_to(__('Expeditions'),'expedition/index');?></li>
                        <li><?php echo link_to(__('RBINS I.G. Numbers'),'igs/index');?></li>
                        <li><?php echo link_to(__('Institutions'),'institution/index');?></li>
                        <li><?php echo link_to(__('People'),'people/index');?></li>
                        <li><?php echo link_to(__('Sampling location'),'gtu/index');?></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="#"><?php echo __('Add');?></a>
            <ul>
                <li><?php echo link_to(__('Specimens'),'specimen/new');?></li>
                <li>
                    <a href="#">Catalogues</a>
                    <ul>
                        <li><?php echo link_to(__('Taxonomy'),'taxonomy/new');?></li>
                        <li><?php echo link_to(__('Chronostratigraphy'),'chronostratigraphy/new');?></li>
                        <li><?php echo link_to(__('Lithostratigraphy'),'lithostratigraphy/new');?></li>
                        <li><?php echo link_to(__('Lithology'),'lithology/new');?></li>
                        <li><?php echo link_to(__('Mineralogy'),'mineralogy/new');?></li>
                        <li><?php echo link_to(__('Collections'),'collection/new');?></li>
                        <li><?php echo link_to(__('Expeditions'),'expedition/new');?></li>
                        <li><?php echo link_to(__('RBINS I.G. Numbers'),'igs/new');?></li>
                        <li><?php echo link_to(__('Institutions'),'institution/new');?></li>
                        <li><?php echo link_to(__('People'),'people/new');?></li>
                        <li><?php echo link_to(__('Sampling location'),'gtu/new');?></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href=""><?php echo __('Administration');?></a>
            <ul>
                <li><?php echo link_to('Reload','account/reload');?></li>
                <li><a href="#">Rights</a></li>
                <li><a href="#">A Super Long truc en NL</a></li>
                <li><a href="#">....</a></li>
                <li>
                	<a href="#"><?php echo __('User');?></a>
                	<ul>
                		<li><?php echo link_to(__('Add'),'user/new');?></li>
                		<li><?php echo link_to(__('Search'),'user/index');?></li>
                	</ul>
                </li>
            </ul>
        </li>
        <li class="exit" ><?php echo link_to(image_tag('exit.png', 'alt=Exit'),'account/logout');?></li>
    </ul>
</div>
