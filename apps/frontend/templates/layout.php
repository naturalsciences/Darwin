<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title') ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <?php include_partial('global/head_menu') ?>
    <?php echo $sf_content ?>
    <script type="text/javascript">
    $(document).ready(function () {
      attachHelpQtip('body');
    });
    </script>
    <div class="clear"></div>
    <div id="load_indicator"><?php echo image_tag('loader.gif');?> <?php echo __('Loading');?></div>
  </body>
</html>
