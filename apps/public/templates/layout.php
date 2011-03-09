<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $sf_user->getCulture() ?>" lang="<?php echo $sf_user->getCulture() ?>">
  <head>
    <?php include_http_metas() ?>  
    <?php include_metas() ?>
    <?php include_javascripts() ?>
    <?php include_stylesheets() ?>
    <title><?php include_slot('title') ?></title>
    <!--[if IE]>
    <?php echo stylesheet_tag('ie.css') ?>
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <table class="all_content">
      <tr>
        <?php include_partial('global/head_menu') ?>
      </tr>
      <tr>
        <td class="content">
          <?php echo $sf_content ?>
        </td>
      </tr>
      <tr>
        <td class="menu_bottom">
          <div class="page">
            <table>
              <tr>
                <td class="browser_img "><ul style="">
                  <li><?php echo __('Recommended browser for DaRWIN :') ; ?></li>
                  <li><?php echo image_tag('chrome.png',array('title' =>'Google Chrome'));?></li>
                  <li><?php echo image_tag('firefox.png',array('title' =>'Firefox >= 3.6'));?></li>
                  <li><?php echo image_tag('Safari.png',array('title' =>'Safari'));?></li>
                </ul></td>
              </tr>
            </table>    
          </div>
        </td>     
      </tr>
    </table>
  </body>
</html>
