<legend><?php echo __("You can also use widgets below for this collection") ; ?></legend>
<ul>
<?php foreach($widget as $name) : ?>
  <li><?php echo sfOutputEscaper::unescape($name) ; ?></li>
<?php endforeach ; ?>
</ul>
