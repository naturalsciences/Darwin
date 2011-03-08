<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $sf_user->getCulture() ?>" lang="<?php echo $sf_user->getCulture() ?>">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php use_stylesheet('menu_pub.css', 'last') ?>

<link rel="shortcut icon" href="/favicon.ico" />

<!--[if lt IE 7.]>
<?php //echo stylesheet_tag('/sf/sf_default/css/ie.css') ?>
<![endif]-->

</head>
<body>
<div class="dw_logo">
  <?php echo link_to(image_tag('photo_bckg.jpg', array('alt' => 'Darwin2')), $sf_request->getHost()) ?>
</div>
<div class="lyt_content">
  <?php echo $sf_content ?>
</div>
</body>
</html>
